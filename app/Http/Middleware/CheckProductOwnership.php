<?php

namespace App\Http\Middleware;

use Closure;

use App\Product;

class CheckProductOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dapatkan produk id dari url
        $product_id = $request->product;
        // dd($product_id);

        // dapatkan product info based on product id
        $product = Product::find($product_id);

        if($product) {
            $product_owner = $product->user_id;

            // dapatkan current logged in user id
            $current_user_id = auth()->id();

            // CHECK JIKA CURRENT USER YG SEDANG ACCESS TIDAK SAMA DENGAN PRODUCT OWNER
            if($current_user_id != $product_owner){
                dd("BERIGAK !! JANGE MENCEROBOH");
            }
        }

        return $next($request);
    }
}
