<?php

namespace App\Nova;

use App\Models\AccountType;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

class Account extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Account>
     */
    public static $model = \App\Models\Account::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'account_number';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'account_number',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable()
                ->textAlign('left'),

            Text::make('Account Number', 'account_number')
                ->required()
                ->textAlign('left'),

            BelongsTo::make('Owner', 'user', User::class)
                ->searchable()
                ->textAlign('left'),

            Currency::make('Amount', 'amount')
                ->currency('GHS')
                ->required()
                ->textAlign('left')
                ->hideFromIndex()
                ->showOnPreview(),

            Select::make('Account Type', 'account_type_id')->options(AccountType::pluck('name', 'id'))
                ->displayUsing(fn ($value) => AccountType::find($value)->name)
                ->sortable()
                ->textAlign('left')
                ->placeholder('Select Account Type')
                ->help('The account type that is to be created, could be a savings account or a checking account.'),

            Boolean::make('Status')
                ->sortable()
                ->textAlign('left'),

            //Markdown::make('Notes', 'notes')
            //Number::make('Quantity');
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
