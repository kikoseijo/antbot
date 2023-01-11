<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class BotLimits implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $user->email != 'demo@sunnyface.com' ){
            if (1 < auth()->user()->bots()->count())
                $fail('You reached bot limit creation.');
        }
    }
}
