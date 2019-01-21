<?php

namespace App;

use App\Notifications\PasswordResetMail;
use App\Notifications\SendEmailVerification;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'twitch_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function island() {
        return $this->hasOne('App\Island');
    }




    /*************************************************
     *  FORGOTTEN PASSWORD
     ************************************************/

    /** Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetMail($token));
    }

    public function isAdmin() {
        return $this->is_admin;
    }




    /*************************************************
     *  EMAIL VERIFICATION
     ************************************************/

    /**
     * Relation with Email-Verification table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function emailVerification() {
        return $this->hasOne('App\EmailVerification');
    }

    /**
     * Saves a new verification in the database and notifies the user via email
     *
     * @return bool true, if email is send
     */
    public function sendEmailVerification() {
        try {
            //Delete existing verifications
            $this->emailVerification()->delete();

            //Create a new token
            $token = str_random(60);

            //Save the new Verification
            $this->emailVerification()->create([
                'token' => $token,
                'requested_at' => Carbon::now(),
            ]);

            //Notify the user
            $this->notify(new SendEmailVerification($token, $this->email));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }




    /*************************************************
     *  JWT DATA
     ************************************************/

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
