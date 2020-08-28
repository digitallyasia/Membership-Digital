<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\PasswordConfirmation;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;

class Organization extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Organization::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'email', 'phone', 'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Image::make('Logo')
                ->disk('images')
                ->creationRules('required')
                ->updateRules('sometimes'),

            Image::make('Card Image')
                ->disk('images'),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:organizations,email')
                ->updateRules('unique:organizations,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            PasswordConfirmation::make('Password Confirmation'),

            Text::make('Phone')
                ->rules('required', 'max:255'),

            Text::make('Address')
                ->rules('required', 'max:255')
                ->hideFromIndex(),

            Text::make('City')
                ->rules('required', 'max:255')
                ->hideFromIndex(),

            Text::make('State')
                ->rules('required', 'max:255')
                ->hideFromIndex(),

            Text::make('Postal Code')
                ->rules('required', 'max:255')
                ->hideFromIndex(),

            Textarea::make('Description')
                ->rules('required'),

            Boolean::make('Auto Join')
                ->rules('required', 'max:255'),

            Image::make('Qrcode')
                ->hideFromIndex()
                ->disk('qrcodes')
                ->onlyOnDetail(),




            Trix::make('Terms & Conditions', 'tnc'),
            Trix::make('Privacy Policy', 'pp'),
            Trix::make('Frequently Asked Questions', 'faq'),

            Text::make('Website')
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => [
                    'placeholder' => 'https://www.example.com'
                ]]),
            Text::make('Facebook')
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => [
                    'placeholder' => 'https://www.facebook.com'
                ]]),
            Text::make('Instagram')
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => [
                    'placeholder' => 'https://www.instagram.com'
                ]]),
            Text::make('Youtube')
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => [
                    'placeholder' => 'https://www.youtube.com'
                ]]),
            Text::make('WhatsApp', 'whatsapp')
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => [
                    'placeholder' => 'https://web.whatsapp.com'
                ]]),
            Text::make('Twitter')
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => [
                    'placeholder' => 'https://www.twitter.com'
                ]]),
            Text::make('Telegram')
                ->hideFromIndex()
                ->withMeta(['extraAttributes' => [
                    'placeholder' => 'https://www.telegram.com'
                ]]),

            BelongsTo::make('Subscription', 'subscription', 'App\Nova\Plan')
                ->withoutTrashed(),
            DateTime::make('Subscription Expire At'),

            HasMany::make('Announcements'),
            HasMany::make('Benefits'),
            HasMany::make('Notifications'),

            BelongsToMany::make('Members', 'members', 'App\Nova\User')
                ->fields(function () {
                    return [
                        Select::make('Status')->options([
                            'accepted' => 'Accepted',
                            'blocked' => 'Blocked',
                            'pending' => 'Pending',
                        ])->displayUsingLabels(),
                    ];
                })->searchable(),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
