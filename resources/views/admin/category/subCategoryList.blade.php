@foreach($subcategories as $subcategory)
    {
    "ID": {{$subcategory->id}},
    "Name": "{{$subcategory->title}}",
    @if($subcategory->sub_category->count())
        "ChildData": [
        @include('admin.category.subCategoryList',['subcategories' => $subcategory->sub_category])
        ]
    @endif
    },
@endforeach
