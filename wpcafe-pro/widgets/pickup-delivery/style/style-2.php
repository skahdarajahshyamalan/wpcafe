<script id="pick_delivery_style2" type="text/x-handlebars-template">
    <div class="wpc-col-lg-4">
        <div class="delivery-pickup-product-wrapper {{ post_empty_class }}">
            <div class="wpc-food-menu-thumb">
                <a href="{{ post_permalink }}">
                {{{ post_image }}}
                </a>
            </div>
            <div class="product-content">
                <h3><a href="{{ post_permalink }}">{{{ post_title }}}</a></h3>
                <p>{{ post_description }}</p>
                <div class="price-and-button">
                    <div class="product-price">
                        {{{ post_price }}}
                    </div>
                    <div class="button-product">{{{ add_to_cart }}}</div>
                </div>
				
            </div>
        </div>
        <h3 class="empty-title">{{ post_title_empty }}</h3>
    </div>

</script>

