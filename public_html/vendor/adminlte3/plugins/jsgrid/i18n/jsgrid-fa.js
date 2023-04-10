(function(jsGrid) {

    jsGrid.locales.fr = {
        grid: {
            noDataContent: "پیدا نشد",
            deleteConfirm: "آیا مطمئن هستید ؟",
            pagerFormat: "صفحات : {first} {prev} {pages} {next} {last} &nbsp;&nbsp; {pageIndex} از {pageCount}",
            pagePrevText: "قبلی",
            pageNextText: "بعدی",
            pageFirstText: "ابتدا",
            pageLastText: "انتها",
            pageNavigatorNextText: "...",
            pageNavigatorPrevText: "...",
            loadMessage: "لطفا صبر کنید ...",
            invalidMessage: "داده های نامعتبر وارد شده است ! هئت"
        },

        loadIndicator: {
            message: "در حال بارگزاری ..."
        },

        fields: {
            control: {
                searchModeButtonTooltip: "جستجو",
                insertModeButtonTooltip: "جدید",
                editButtonTooltip: "ویرایش",
                deleteButtonTooltip: "حذف",
                searchButtonTooltip: "جستجو",
                clearFilterButtonTooltip: "حذف جستجو",
                insertButtonTooltip: "درج",
                updateButtonTooltip: "بروزرسانی",
                cancelEditButtonTooltip: "لغو"
            }
        },

        validators: {
            required: { message: "اجباری" },
            rangeLength: { message: "طول رشته نادرست" },
            minLength: { message: "کمتر از" },
            maxLength: { message: "بیشتر از" },
            pattern: { message: "الگو" },
            range: { message: "محدوده" },
            min: { message: "کوچکتر" },
            max: { message: "بزرگتر" }
        }
    };

}(jsGrid, jQuery));

