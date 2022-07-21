$year_wise_expense = Transection::selectRaw("sum(`amount`) as 'total_amount', YEAR(`created_at`) as 'year', MONTH(`created_at`) as 'month'")->where(['tran_type' => 'Expense'])
            ->groupBy('month')
            ->orderBy('year')
            ->get();

        $year_wise_income = Transection::selectRaw("sum(`amount`) as 'total_amount', YEAR(`created_at`) as 'year', MONTH(`created_at`) as 'month'")->where(['tran_type' => 'Income'])
            ->groupBy('month')
            ->orderBy('year')
            ->get();
https://www.tutsmake.com/laravel-get-current-date-week-month-wise-year-data/      


if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $product_ids = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })->pluck('id');
            if($product_ids->count()==0)
            {
                $product_ids = Translation::where('translationable_type', 'App\Model\Product')
                    ->where('key', 'name')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('value', 'like', "%{$value}%");
                        }
                    })
                    ->pluck('translationable_id');
            }
            $query = $porduct_data->WhereIn('id', $product_ids);
        }


MUltiple condidion filter
$reviews = Review::with(['product', 'customer'])->where(function($q) use($request){
                $q->orWhere('customer_id',$request->customer_id)
                ->orWhere('product_id',$request->product_id)
                ->orWhere('product_id',$request->status);
              });

{{ (request()->get("customer_id") != null && request()->get("customer_id") == $item->id )  ? "selected" : "" }}


https://stackoverflow.com/questions/62599531/laravel-multiple-filter-with-search
