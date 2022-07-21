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

https://stackoverflow.com/questions/63936934/laravel-6-search-query-with-multiple-conditions

https://stackoverflow.com/questions/62599531/laravel-multiple-filter-with-search


function list(Request $request)
    {
        //dd($request->all());
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $product_id = Product::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
            })->pluck('id')->toArray();
            $customer_id = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%");
                }
            })->pluck('id')->toArray();
            $reviews = Review::WhereIn('product_id',  $product_id)->orWhereIn('customer_id', $customer_id);
            $query_param = ['search' => $request['search']];
        } elseif ($request->customer_id || $request->product_id || $request->status || $request->from || $request->to) {

            $reviews = Review::when($request->product_id != null, function($q) {
                $q->where('product_id', request('product_id'));
            })->when($request->customer_id != null, function($q){
                $q->where('customer_id', request('customer_id'));
            })->when($request->status != null, function($q){
                $q->where('status', request('status'));
            })->when($request->from && $request->to, function($q) use ($request){
                $q->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
                //dd($q);
            });

        } else {
            $reviews = Review::with(['product', 'customer']);
        }
        $reviews = $reviews->latest()->paginate(Helpers::pagination_limit());
        $products = Product::select('id', 'name')->get();
        $customers = User::select('id', 'name', 'f_name', 'l_name')->get();
        return view('admin-views.reviews.list', compact('reviews', 'search', 'products', 'customers'));
    }
