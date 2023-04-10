<include name="layouts/header.html"/>
<div class="container">
    <div class="row mb-15">

        <widget type="gallery" name="home:slider" section="صفحه نخست">
            <div class="col-12 col-lg-9">
                <div class="index-slider bg-white shadow-sm rounded position-relative">
                    <div class="owl-carousel"
                         options="{ items: 1 , dots: true, autoplay: true, autoplayTimeout: 5000, autoplayHoverPause: true, smartSpeed: 500, loop:true, rtl: true }">
                        <loop src="@@items">
                            <a class="d-block" href="@@url" title="@@title">
                                <img class="d-block w-100 rounded" src="@@image?m=crop&w=979&h=334&q=veryhigh"
                                     alt="@@title"/>
                            </a>
                        </loop>
                    </div>
                    <if terms="@@user.admin">
                        <a href="@@editurl"
                           class="btn btn-sm btn-edit edit-link edit-link-widget inner-edit-placer">
                            <i class="fa fa-pencil"></i>
                            ویرایش
                        </a>
                    </if>
                </div>
            </div>
        </widget>

        <widget type="content" name="home:header-banner" section="صفحه نخست">
            <div class="col-3 d-none d-lg-block">
                <div class="header-banner h-100 w-100 position-relative">
                    <a class="d-flex flex-column h-100 w-100 position-absolute overflow-hidden justify-content-center align-items-center rounded shadow-sm"
                       href="@@url">
                        <img class="d-block h-100" src="@@image?m=crop&w=316&h=334&q=veryhigh"/>
                    </a>
                    <if terms="@@user.admin">
                        <a href="@@editurl"
                           class="btn btn-sm btn-edit edit-link edit-link-widget inner-edit-placer">
                            <i class="fa fa-pencil"></i>
                            ویرایش
                        </a>
                    </if>
                </div>
            </div>
        </widget>

    </div>

    <widget type="contentlist" name="home:features" section="ابزارک‌های عمومی">
        <div class="mb-15 rounded bg-white shadow-sm px-30 px-sm-40 px-md-50 px-xl-100 pt-30">
            <if terms="@@user.admin">
                <div class="mb-30 text-center">
                    <a href="@@addurl" class="btn btn-sm btn-create add-link add-link-widget">
                        <i class="fa fa-pencil"></i>
                        اضافه کردن
                    </a>
                    <a href="@@editurl"
                       class="btn btn-sm btn-edit edit-link edit-link-widget inner-edit-placer">
                        <i class="fa fa-pencil"></i>
                        تنظیمات ابزارک
                    </a>
                </div>
            </if>
            <div class="row align-items-center justify-content-lg-center">
                <loop src="@@items">
                    <div class="col-12 col-sm-6 col-lg-3 mb-30">
                        <div class="d-flex align-items-center">
                            <img src='@@image.default("/site/resources/images/empty.jpg")?m=thumb&w=54&h=54&q=high'
                                 class="ml-15"
                                 alt="@@title">
                            <div>
                                <h3 class="text-xs mb-0">@@title</h3>
                                <h6 class="text-xxs text-muted font-weight-normal mb-0">@@slogan</h6>
                            </div>
                        </div>
                        <if terms="@@user.admin">
                            <a href="@@editurl"
                               class="btn btn-sm btn-edit edit-link edit-link-widget text-xxs mt-10">
                                <i class="fa fa-pencil"></i>
                                ویرایش
                            </a>
                        </if>
                    </div>
                </loop>
            </div>
        </div>
    </widget>

    <div class="row">
        <div class="col-12 col-lg-3 mb-lg-15">

            <widget type="content" name="home:sidebar-socials" section="صفحه نخست">
                <div class="index-sidebar d-flex flex-column h-100 position-relative">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-12">
                            <a href="@@telegram"
                               class="index-sidebar-social-telegram rounded d-flex align-items-center justify-content-center mb-10 py-3 px-20">
                                <i class="fa fa-send text-lg"></i>
                                <span>کانال تلگرام</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-12">
                            <a href="@@instagram"
                               class="index-sidebar-social-instagram rounded d-flex align-items-center justify-content-center mb-15 py-3 px-20">
                                <i class="fa fa-instagram"></i>
                                <span>صفحه اینستاگرام</span>
                            </a>
                        </div>
                    </div>
                    <div class="d-none d-lg-flex flex-grow-1">
                        <div class="index-sidebar-banner h-100 w-100 position-relative">
                            <a class="d-flex flex-column h-100 w-100 position-absolute overflow-hidden justify-content-center align-items-center rounded shadow-sm"
                               href="@@url">
                                <img class="d-block h-100" src="@@image?m=crop&w=317&h=383&q=veryhigh"/>
                            </a>
                        </div>
                    </div>
                    <if terms="@@user.admin">
                        <a href="@@editurl"
                           class="btn btn-sm btn-edit edit-link edit-link-widget inner-edit-placer">
                            <i class="fa fa-pencil"></i>
                            ویرایش
                        </a>
                    </if>
                </div>
            </widget>

        </div>

        <widget type="products" name="home:products1" section="صفحه نخست">
            <div class="col-12 col-lg-9 mb-15">
                <div class="special-offers position-relative bg-white shadow-sm rounded">
                    <if terms="@@user.admin">
                        <div class="mb-n50">
                            <a href="@@editurl"
                               class="btn btn-sm btn-edit edit-link edit-link-widget align-top mt-20 mr-20 text-sm position-relative">
                                <i class="fa fa-pencil"></i>
                                ویرایش
                            </a>
                        </div>
                    </if>
                    <div class="owl-carousel position-static special-offers-carousel">
                        <loop src="@@items">
                            <a href="@@url"
                               class='h-100 align-items-center d-flex px-25 pt-25 p-sm-35 p-md-45 p-lg-50 @@outofstock.whenever("store-product-outofstock")'>
                                <div class="row flex-grow-1 align-items-sm-center mx-n15 h-100">
                                    <div class="col-12 col-md-5 px-15">
                                        <img src='@@image.default("/site/resources/images/empty.jpg")?m=crop&w=500&h=500&q=high'
                                             class="w-100 d-block"
                                             alt="@@title">
                                    </div>
                                    <div class="col-12 col-md-7 text-center text-md-right px-15 mt-20 mt-md-0 d-flex flex-column">
                                        <div class="d-flex flex-column flex-grow-1">
                                            <h3 class="text-xl text-secondary mb-20">@@title</h3>
                                            <if terms="@@outofstock.false()">
                                                <if terms="@@compareprice.exists">
                                                    <div class="text-lg text-muted mb-2 mb-md-0">
                                                        @@compareprice <span class="text-xxs">تومان</span>
                                                    </div>
                                                </if>
                                                <div class="d-flex justify-content-center justify-content-md-start align-items-center">
                                                    <if terms="@@price.exists">
                                                        <div class="h3 text-red mb-0">
                                                            @@price <span class="text-sm">تومان</span>
                                                        </div>
                                                    </if>
                                                    <if terms='@@discount.morethan("0")'>
                                                        <div class="bg-red text-white px-4 py-half h4 mb-0 rounded-pill mr-25">
                                                            @@discount٪ <span class="text-sm">تخفیف</span>
                                                        </div>
                                                    </if>
                                                </div>
                                            </if>
                                        </div>
                                        <if terms="@@expired.false() && @@expiration.exists()">
                                            <div auto-show>
                                                <div class="special-offer-countdown mx-n25 mx-sm-0 justify-content-center d-flex d-sm-inline-flex bg-light p-10 rounded text-center flex-row-reverse mt-30"
                                                     count-down="@@expiration.time.subtract.total.seconds" auto-show>
                                                    <div class="special-offer-countdown-col p-10 mr-2 bg-white rounded shadow-sm">
                                                            <span class="special-offer-countdown-number h4 font-weight-bold text-secondary mb-n2 d-block">
                                                                {{days}}
                                                            </span>
                                                        <span class="special-offer-countdown-label text-muted text-xxs d-block">
                                                                روز
                                                            </span>
                                                    </div>
                                                    <div class="special-offer-countdown-col p-10 mr-2 bg-white rounded shadow-sm">
                                                            <span class="special-offer-countdown-number h4 font-weight-bold text-secondary mb-n2 d-block">
                                                                {{hours}}
                                                            </span>
                                                        <span class="special-offer-countdown-label text-muted text-xxs d-block">
                                                                ساعت
                                                            </span>
                                                    </div>
                                                    <div class="special-offer-countdown-col p-10 mr-2 bg-white rounded shadow-sm">
                                                            <span class="special-offer-countdown-number h4 font-weight-bold text-secondary mb-n2 d-block">
                                                                {{minutes}}
                                                            </span>
                                                        <span class="special-offer-countdown-label text-muted text-xxs d-block">
                                                                دقیقه
                                                            </span>
                                                    </div>
                                                    <div class="special-offer-countdown-col p-2 bg-white rounded shadow-sm">
                                                            <span class="special-offer-countdown-number h4 font-weight-bold text-secondary mb-n2 d-block">
                                                                {{seconds}}
                                                            </span>
                                                        <span class="special-offer-countdown-label text-muted text-xxs d-block">
                                                                ثانیه
                                                            </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </if>
                                        <if terms="@@outofstock">
                                                <span class="store-product-outofstock-message">
                                                    ناموجود
                                                </span>
                                        </if>
                                    </div>
                                </div>
                            </a>
                        </loop>
                    </div>
                    <div class="special-offers-nav bg-lighten rounded-bottom border-top px-70 py-15 d-none d-sm-block">
                        <div class="owl-carousel mx-n1 w-auto">
                            <loop src="@@items">
                                <div class="px-1 py-half">
                                    <div class="special-offers-nav-item text-center rounded d-block text-xs py-2 px-15">
                                        <span class="d-block w-100 overflow-hidden">@@title</span>
                                    </div>
                                </div>
                            </loop>
                        </div>
                    </div>
                </div>
            </div>
        </widget>

    </div>

    <widget type="gallery" name="home:gallery1" section="صفحه نخست">
        <div class="row justify-content-center">
            <loop src="@@items">
                <div class="col-6 col-md-4 col-lg-3 mb-15">
                    <a class="d-block" href="@@url" title="@@title">
                        <img class="d-block w-100 rounded shadow-sm" src="@@image?m=thumb&w=316&h=316&q=veryhigh"
                             alt="@@title"/>
                    </a>
                </div>
            </loop>
        </div>
        <if terms="@@user.admin">
            <div class="mb-15 text-center mt-nhalf">
                <a href="@@editurl"
                   class="btn btn-sm btn-edit edit-link edit-link-widget inner-edit-placer">
                    <i class="fa fa-pencil"></i>
                    ویرایش
                </a>
            </div>
        </if>
    </widget>

    <widget type="products" name="home:products2" section="صفحه نخست">
        <div class="index-products rounded shadow-sm bg-white position-relative mb-15">
            <div class="d-flex align-items-center border-bottom py-15 py-sm-20 px-25">
                <h2 class="index-products-carousel-title text-lg mb-0">@@title</h2>
                <if terms="@@user.admin">
                    <a href="@@editurl"
                       class="btn btn-sm btn-edit edit-link edit-link-widget mr-15">
                        <i class="fa fa-pencil"></i>
                        ویرایش
                    </a>
                </if>
            </div>
            <div class="index-products-carousel owl-carousel position-static"
                 options="{ navContainerClass:'owl-nav py-3 position-absolute d-none d-md-block', navText: ['<i class=\'btn btn-light btn-sm text-lg px-15 py-half ml-2 fa fa-angle-right\'></i>', '<i class=\'btn btn-light btn-sm text-lg px-15 py-half fa fa-angle-left\'></i>'], nav:true, autoplay: false, loop:true, responsive:{ 0:{ items: 1 }, 576: { items: 2 }, 992: { items: 3 }, 1200: { items: 4 } }  }">
                <loop src="@@items">
                    <div class="index-products-carousel-item border-left h-100">
                        <a href="@@url"
                           class='store-product px-30 pt-30 pb-80 shadow-none bg-transparent d-block @@outofstock.whenever("store-product-outofstock")'
                           itemprop="itemListElement" itemscope itemtype="http://schema.org/Product">
                            <div class="store-product-image store-compact-product-image">
                                <div class="store-product-image-link"
                                     title="@@metatitle">
                                    <img src='@@image.default("/site/resources/images/empty.jpg")?m=crop&w=500&h=500&q=high'
                                         class="img-fluid center-block store-product-image-element"
                                         alt="@@title" itemprop="image">
                                </div>
                            </div>
                            <h3 class="store-product-title" itemprop="name">
                            <span class="store-product-link"
                                  title="@@metatitle" itemprop="url mainEntityOfPage">
                                @@title
                            </span>
                            </h3>
                            <if terms="@@outofstock.false()">
                                <if terms="@@compareprice.exists">
                                    <span class="store-product-compare-price">
                                        @@compareprice
                                    </span>
                                </if>
                                <if terms="@@price.exists">
                                    <span class="store-product-price"
                                          itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <meta itemprop="price" content="@@price.number.rials()">
                                        <meta itemprop="priceCurrency" content="IRR">
                                        <span>@@price تومان</span>
                                    </span>
                                </if>
                                <if terms='@@discount.morethan("0")'>
                                    <span class="store-product-discount">
                                        @@discount٪
                                    </span>
                                </if>
                            </if>
                            <if terms="@@outofstock">
                                <span class="store-product-outofstock-message">
                                    ناموجود
                                </span>
                            </if>
                            <meta itemprop="position" content="@@index">
                        </a>
                        <if terms="@@outofstock.false()">
                            <div class="text-center mt-n50 position-relative">
                                <button class="btn btn-sm btn-primary store-product-quick-view" ng-click="quickview(@@id, '/site/cart')">
                                    <if terms="@@inquiry">استعلام محصول</if>
                                    <if terms="@@inquiry.false()">
                                        <i class="fa fa-shopping-cart"></i>
                                        خرید
                                    </if>
                                </button>
                            </div>
                        </if>
                    </div>
                </loop>
            </div>
        </div>
    </widget>

    <widget type="gallery" name="home:gallery2" section="صفحه نخست">
        <div class="row justify-content-center">
            <loop src="@@items">
                <div class="col-12 col-md-6 mb-15">
                    <a class="d-block" href="@@url" title="@@title">
                        <img class="d-block w-100 rounded shadow-sm" src="@@image?m=thumb&w=648&h=648&q=veryhigh"
                             alt="@@title"/>
                    </a>
                </div>
            </loop>
        </div>
        <if terms="@@user.admin">
            <div class="mb-15 text-center mt-nhalf">
                <a href="@@editurl"
                   class="btn btn-sm btn-edit edit-link edit-link-widget inner-edit-placer">
                    <i class="fa fa-pencil"></i>
                    ویرایش
                </a>
            </div>
        </if>
    </widget>

    <widget type="products" name="home:products3" section="صفحه نخست">
        <div class="index-products rounded shadow-sm bg-white position-relative mb-15">
            <div class="d-flex align-items-center border-bottom py-15 py-sm-20 px-25">
                <h2 class="index-products-carousel-title text-lg mb-0">@@title</h2>
                <if terms="@@user.admin">
                    <a href="@@editurl"
                       class="btn btn-sm btn-edit edit-link edit-link-widget mr-15">
                        <i class="fa fa-pencil"></i>
                        ویرایش
                    </a>
                </if>
            </div>
            <div class="index-products-carousel owl-carousel position-static"
                 options="{ navContainerClass:'owl-nav py-3 position-absolute d-none d-md-block', navText: ['<i class=\'btn btn-light btn-sm text-lg px-15 py-half ml-2 fa fa-angle-right\'></i>', '<i class=\'btn btn-light btn-sm text-lg px-15 py-half fa fa-angle-left\'></i>'], nav:true, autoplay: false, loop:true, responsive:{ 0:{ items: 1 }, 576: { items: 2 }, 992: { items: 3 }, 1200: { items: 4 } }  }">
                <loop src="@@items">
                    <div class="index-products-carousel-item border-left h-100">
                        <a href="@@url"
                           class='store-product px-30 pt-30 pb-80 shadow-none bg-transparent d-block @@outofstock.whenever("store-product-outofstock")'
                           itemprop="itemListElement" itemscope itemtype="http://schema.org/Product">
                            <div class="store-product-image store-compact-product-image">
                                <div class="store-product-image-link"
                                     title="@@metatitle">
                                    <img src='@@image.default("/site/resources/images/empty.jpg")?m=crop&w=500&h=500&q=high'
                                         class="img-fluid center-block store-product-image-element"
                                         alt="@@title" itemprop="image">
                                </div>
                            </div>
                            <h3 class="store-product-title" itemprop="name">
                            <span class="store-product-link"
                                  title="@@metatitle" itemprop="url mainEntityOfPage">
                                @@title
                            </span>
                            </h3>
                            <if terms="@@outofstock.false()">
                                <if terms="@@compareprice.exists">
                                    <span class="store-product-compare-price">
                                        @@compareprice
                                    </span>
                                </if>
                                <if terms="@@price.exists">
                                    <span class="store-product-price"
                                          itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <meta itemprop="price" content="@@price.number.rials()">
                                        <meta itemprop="priceCurrency" content="IRR">
                                        <span>@@price تومان</span>
                                    </span>
                                </if>
                                <if terms='@@discount.morethan("0")'>
                                    <span class="store-product-discount">
                                        @@discount٪
                                    </span>
                                </if>
                            </if>
                            <if terms="@@outofstock">
                                <span class="store-product-outofstock-message">
                                    ناموجود
                                </span>
                            </if>
                            <meta itemprop="position" content="@@index">
                        </a>
                        <if terms="@@outofstock.false()">
                            <div class="text-center mt-n50 position-relative">
                                <button class="btn btn-sm btn-primary store-product-quick-view" ng-click="quickview(@@id, '/site/cart')">
                                    <if terms="@@inquiry">استعلام محصول</if>
                                    <if terms="@@inquiry.false()">
                                        <i class="fa fa-shopping-cart"></i>
                                        خرید
                                    </if>
                                </button>
                            </div>
                        </if>
                    </div>
                </loop>
            </div>
        </div>
    </widget>

</div>
<include name="layouts/footer.html"/>
