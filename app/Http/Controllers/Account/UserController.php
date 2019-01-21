<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\Account\DeleteAccountRequest;
use App\Http\Requests\Account\RegisterNewAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Island;
use App\User;
use Faker\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\LoginUserRequest;
use App\Http\Resources\Account\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'storeNewUser']]);
        $this->middleware('verified_email', ['only' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function me()
    {
        return new UserResource(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Erfolgreich abgemeldet.']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Stores a new User in the database
     *
     * @param RegisterNewAccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeNewUser(RegisterNewAccountRequest $request)
    {
        $faker = \Faker\Factory::create('de_DE');

        //Logout the currently logged in User (since a new one is registered)
        if (auth()->check()) {
            auth()->logout();
        }

        //Check if the user accepted the agb
        if ($request->agb_accepted) {
            //agb accepted
        } else {
            return response()->json([
                'message' => 'Die Daten sind fehlerhaft.',
                'errors' => [
                    'agb_accepted' => "Die AGB's müssen akzeptiert werden."
                ]
            ], 422);
        }

        $user = User::create([
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        //$user->sendEmailVerification();

        //Decide on what direction the island will go to
        if ($request['island_direction'] === 'north_east')
            $directionPlayerCount = Island::where('position_x', '>', 0)->where('position_y', '>', 0)->count();
        else if ($request['island_direction'] === 'south_east')
            $directionPlayerCount = Island::where('position_x', '>', 0)->where('position_y', '<', 0)->count();
        else if ($request['island_direction'] === 'south_west')
            $directionPlayerCount = Island::where('position_x', '<', 0)->where('position_y', '<', 0)->count();
        else if ($request['island_direction'] === 'north_west')
            $directionPlayerCount = Island::where('position_x', '<', 0)->where('position_y', '>', 0)->count();

        /*
         * Take playercount
         * substract island count each layer until the
         */
        $ringNo = $sum = 1;
        while ($sum <= ($directionPlayerCount+1)) {
            $ringNo++;
            $sum += $ringNo;
        }

        $islandCount = 1;
        $x = $y = 0;
        while ($islandCount > 0) {
            //find a random spot on the ring
            if (rand(0, 1) < 1) {
                $x = $ringNo;
                $y = rand(1, $ringNo);
            } else {
                $x = rand(1, $ringNo);
                $y = $ringNo;
            }
            //Adjust direction
            switch ($request['island_direction']) {
                case 'north_east' :
                    break;
                case 'south_east' :
                    $y *= -1;
                    break;
                case 'south_west' :
                    $x *= -1;
                    $y *= -1;
                    break;
                case 'north_west':
                    $x *= -1;
                    break;
            }
            //Check if the space is still empty
            $islandCount = Island::where('position_x', $x)->where('position_y', $y)->count();
        }
        //Create island
        $user->island()->create([
            'name' => $faker->word,
            'position_x' => $x,
            'position_y' => $y
        ]);

        return response()->json(['message' => 'Nutzer erfolgreich erstellt.'], 201);
    }

    /**
     * Deletes the logged in Account from the system
     *
     * @param DeleteAccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function deleteUserFromDatabase(DeleteAccountRequest $request, $id)
    {

        if ($id != auth()->user()->id) {
            return response()->json([
                'message' => 'Sie können nur den eigenen Benutzer löschen.'
            ], 403);
        }

        //Get the logged in user
        $user = auth()->user();

        auth()->logout();
        $user->delete();

        return response()->json([
            'message' => 'Account erfolgreich gelöscht.'
        ], 201);
    }


    /**
     * Updates Account-Data of the logged in user
     *
     * @param UpdateAccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function updateUserData(UpdateAccountRequest $request)
    {
        //Get the logged in user
        $user = auth()->user();

        //Check if password is correct
        if (Hash::check($request['password'], $user->getAuthPassword())) {
            //Password is correct
        } else {
            return response()->json([
                'message' => 'Die Daten sind fehlerhaft.',
                'errors' => [
                    'password' => "Das Password ist nicht korrekt."
                ]
            ], 422);
        }

        //Change data
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];
        $user->description = $request['description'];
        $user->street = $request['street'];
        $user->postcode = $request['postcode'];
        $user->city = $request['city'];
        $user->phone = $request['phone'];
        $user->date_of_birth = $request['date_of_birth'];
        $user->gender = $request['gender'];

        //Check if the password is changed and change it, when it has changed
        if (isset($request->new_password)) {
            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        return response()->json([
            'message' => 'Account erfolgreich geändert.'
        ], 201);
    }

}
