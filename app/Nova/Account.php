<?php

namespace App\Nova;

//use App\Models\AccountType;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo;

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

    public static $tableStyle = 'tight';

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

            Currency::make('Amount', 'amount')
                ->currency('USD')
                ->required()
                ->textAlign('left'),
                //->hideFromIndex()
                //->showOnPreview(),

            //Text::make('Account Type', 'account_type_id')
                //->textAlign('left'),

        BelongsTo::make('Account Type', 'accountType')
                ->textAlign('left'),
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
