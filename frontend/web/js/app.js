$(function(){
    const $addToCart = $('.btn-add-to-cart');
    $addToCart.click(ev =>
        {
            ev.preventDefault();
            const $this = $(ev.target);
            const id = $this.closest('.product-item').data('key');
            console.log(id);
            $.ajax({
                method:'POST',
                url:$this.attr('href'),
                data: {id},
                success:function (arguments){
                    console.log(arguments)
                }
            })

        }


    )
});