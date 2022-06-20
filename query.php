$year_wise_expense = Transection::selectRaw("sum(`amount`) as 'total_amount', YEAR(`created_at`) as 'year', MONTH(`created_at`) as 'month'")->where(['tran_type' => 'Expense'])
            ->groupBy('month')
            ->orderBy('year')
            ->get();

        $year_wise_income = Transection::selectRaw("sum(`amount`) as 'total_amount', YEAR(`created_at`) as 'year', MONTH(`created_at`) as 'month'")->where(['tran_type' => 'Income'])
            ->groupBy('month')
            ->orderBy('year')
            ->get();
https://www.tutsmake.com/laravel-get-current-date-week-month-wise-year-data/            
