<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <section>

        <div class="inputNameShopify" style="margin: 150px;">


            <form action="shopify" method="post">

                <label>Nhập tên cửa hàng Shopify </label>
                <div>
                    <input name="nameShopify">
                </div>
                @csrf
                <button style="margin: 10px;">Button</button>
            </form>
        </div>

    </section>
</body>

</html>