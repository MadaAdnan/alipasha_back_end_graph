<?php

namespace App\Filament\Seller\Pages;

use App\Enums\LevelUserEnum;
use App\Models\City;
use App\Models\Plan;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Pages\Auth\Register;
use Filament\Pages\Page;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class RegisterPage extends Register
{

    protected function sendEmailVerificationNotification(Model $user): void
    {
        if (! $user instanceof MustVerifyEmail) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            return;
        }

        if (! method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new \Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        $notification = app(VerifyEmail::class);
      //  $notification->url = Filament::getVerifyEmailUrl($user);

        $user->notify($notification);
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        TextInput::make('phone')->label('رقم الهاتف')->required(),
                        TextInput::make('address')->label('العنوان التفصيلي')->required(),
                        TextInput::make('seller_name')->label('اسم المتجر')->required(),
                        Select::make('main_id')->options(City::where('is_main',true)->pluck('name','id'))->live()->dehydrated(false)->label('المحافظة'),
                        Select::make('city_id')->options(fn($get)=>City::where('city_id',$get('main_id'))->pluck('name','id'))->label('المدينة')->required(),
                        TextInput::make('affiliate')->label('كود الإحالة'),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),

                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function handleRegistration(array $data): Model
    {
        $data['is_seller']=true;
        $data['level']=LevelUserEnum::SELLER->value;
        if($data['affiliate']!=null){
            $user=User::where('affiliate',$data['affiliate'])->first();
            $data['user_id']=$user->id;
        }
        unset($data['affiliate']);

        $user= $this->getUserModel()::create($data);
        $plan=Plan::where(['is_active'=>1,'price'=>0])->first();
        if($plan){
            $user->plans()->syncWithPivotValues([$plan->id],['subscription_date'=>now(),'expired_date'=>now()->addYear()]);
        }

        return $user;
    }
}
