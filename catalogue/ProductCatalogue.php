<?php

class ProductCatalog {
    public $sqlRows;

    function __construct($sqlRows) {
        $this->sqlRows = $sqlRows;
    }

    public static function echoStyle() {
?>
        <style>
            .product:hover {
                text-decoration: none;
                background-color: #ffd566;
                color: inherit;
            }

            .product {
                background-color: white;
                color: inherit;
                border-radius: 4vh;
                box-shadow: 0px 0px 10px #C6C6C6;
                margin-bottom: 20px
            }
        </style>
    <?php
    }

    public function echoHtml() {
    ?>
        <div class="row">
            <?php
            while ($row = $this->sqlRows->fetch_array()) {
                $product = "productDetails.php?pid=$row[ProductID]";
                $formattedPrice = number_format($row["Price"], 2);

                $isOut = $row["Quantity"] <= 0;

            ?>

                <div class="col-sm-6 col-md-4 col-lg-3 py-3">
                    <a href="<?= $product ?>" class="card product">
                        <div class="bg-image m-2 text-center shadow-1-strong rounded-circle overflow-hidden flex-fill" style="aspect-ratio: 1 / 1;background-repeat: no-repeat;background-size:cover;background-image: url('../Images/products/<?= $row["ProductImage"] ?>');">
                            <?php if ($isOut) { ?>
                                <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <h1 class="text-white mb-0 font-weight-bold font-italic">OUT OF STOCK</h1>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="card-body bg-transparent">
                            <h5 class="card-title"><?= $row["ProductTitle"] ?></h5>
                            <p class="card-text">S$ <?= $formattedPrice ?></p>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
<?php

    }
}
