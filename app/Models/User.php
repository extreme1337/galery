<?php

namespace App\Models;

use App\Models\Social;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'profile_image',
        'cover_image',
        'city',
        'country',
        'about_me',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function updateSettings($data){
        this->update($data['user']);
        $this->updateSocialProfile($data['social']);
        $this->updateOptions($data['options']);
    }

    public function profileImageUrl()
    {
        return Storage::url($this->profile_image ? $this->profile_image : "users/user-default.png");
    }

    public function coverImageUrl()
    {
        return Storage::url($this->cover_image);
    }


    public function hasCoverImage()
    {
        return !!$this->cover_image;
    }

    public static function makeDirectory()
    {
        $directory = 'users';
        Storage::makeDirectory($directory);
        return $directory;
    }

    protected function updateOptions($options)
    {
        $this->setting()->update($options);
    }

    protected function updateSocialProfile($social){
        // if($this->social()->exist()){
        //     $this->social()->update($social);
        // }else{
        //     $this->social()->create($social);
        // }

        Social::updateOrCreate(
            ['user_id'=>$this->id],
            $social
        );
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function social(){
        return $this->hasOne(Social::class)->withDefault();
    }

    public function setting()
    {
        return $this->hasOne(Setting::class)->withDefault();
    }

    public function url()
    {
        return route('author.show', $this->username);
    }

    public function inlineProfile()
    {
        return collect([
            $this->name,
            trim(join("/", [$this->city, $this->country]), "/"),
            "Member since " . $this->created_at->toFormattedDateString(),
            $this->getImagesCount()
        ])->filter()->implode(" ??? ");
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->setting()->create([
                "email_notification" => [
                    "new_comment" => 1,
                    "new_image" => 1
                ]
            ]);
        });
    }
    // public function recentSocial(){
    //     return $this->hasOne(Social::class)->latestOfMany();
    // }
    
    // public function oldestSocial(){
    //     return $this->hasOne(Social::class)->oldestOfMany();
    // }

    // public function socialPriority(){
    //     return $this->hasOne(Social::class)->ofMany('priority', 'min');
    // }

    public function getImagesCount()
    {
        $imagesCount = $this->images()->published()->count();
        return $imagesCount . ' ' . str()->plural('image', $imagesCount);
    }
}