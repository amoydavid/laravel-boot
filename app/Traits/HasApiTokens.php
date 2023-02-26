<?php

namespace App\Traits;

use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;

trait HasApiTokens
{
    use \Laravel\Sanctum\HasApiTokens;

    /**
     * 添加一个带过期时间的token
     * @param string $name
     * @param array $abilities
     * @param int $expireDays
     * @return NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'], int $expireDays = 30)
    {
        $expires = '';
        if($expireDays > 0) {
            $expires = Carbon::now()->addDays($expireDays)->format('Y-m-d H:i:s');
        }
        /**
         * @var $token PersonalAccessToken
         */
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'expired_at' => $expires,
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }
}
