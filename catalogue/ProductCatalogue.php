<?php

class ProductCatalog {
    public $sqlRows;
    public $baseUri;

    function __construct($sqlRows, $baseUri = "..") {
        $this->sqlRows = $sqlRows;
        $this->baseUri = $baseUri;
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
                border-radius: 4vh !important;
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
                $product = $this->baseUri == "." ? "./catalogue/" : "./";
                $product .= "productDetails.php?pid=$row[ProductID]";
                $formattedPrice = number_format($row["Price"], 2);

                $isOut = $row["Quantity"] <= 0;

            ?>

                <div class="col-sm-6 col-md-4 col-lg-3 py-3">
                    <a href="<?= $product ?>" class="card product h-100">
                        <div class="bg-image m-2 text-center shadow-1-strong rounded-circle overflow-hidden " style="aspect-ratio: 1 / 1;background-repeat: no-repeat;background-size:cover;background-image: url('<?= $this->baseUri ?>/Images/products/<?= $row["ProductImage"] ?>');">
                            <?php if ($isOut) { ?>
                                <div class="mask w-100 h-100" style="background-color: rgba(0, 0, 0, 0.6);">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <h1 class="text-white mb-0 font-weight-bold font-italic">OUT OF STOCK</h1>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="card-body bg-transparent">
                            <h5 class="card-title"><?= $row["ProductTitle"] ?></h5>
                            <?php
                            if ($row["OfferedPrice"] ?? false) {
                                $pctg = sprintf("-%.0f%%", ($row["Price"] - $row["OfferedPrice"]) / $row["Price"] * 100);

                            ?>
                                <span class="badge badge-pill badge-primary"><?= $pctg ?></span>
                                <p class="card-text font-weight-bold text-danger py-1 pr-1">
                                    <span class="font-weight-normal text-muted" style="text-decoration: line-through;">S$ <?= $formattedPrice ?></span>
                                    S$ <?= number_format($row["OfferedPrice"], 2) ?>
                                </p>
                            <?php } else { ?>
                                <p class="card-text font-weight-bold text-danger">S$ <?= $formattedPrice ?></p>
                            <?php } ?>
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
