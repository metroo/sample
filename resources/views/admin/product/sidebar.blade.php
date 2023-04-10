<div class=" sidebar-item ">
    <div class="col-12  ">
        <div class="col-12">
            دسته بندی ها
        </div>
        <div class="list-group mt-4 mb-2 card border-secondary">
            @foreach ($categories as $category)
                <a href="{{ route('cities.cat.show', ['city' => $citySlug , 'category' => $category->slug])}}"
                   class="list-group-item  ">
                     {{ $category->title }}
                </a>
            @endforeach
        </div>
        <div class="card mb-2  border-secondary  "  >
            <div class="card-body">
                <h5 class="card-title">قیمت</h5>
                <p class="card-text">
                <div class="form-group">
                    <label>حداقل</label>
                    <select class="form-control select2price col-12" >
                        <option> </option>
                        <option>10 هزار</option>
                        <option>25 هزار</option>
                        <option>50 هزار</option>
                        <option>70 هزار</option>
                        <option>100 هزار</option>
                        <option>150 هزار</option>
                        <option>300 هزار</option>
                        <option>500 هزار</option>
                        <option>1 میلیون</option>
                        <option>2 میلیون</option>
                        <option>5 میلیون</option>
                        <option>10 میلیون</option>
                        <option>50 میلیون</option>
                        <option>100 میلیون</option>
                        <option>200 میلیون</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>حداکثر</label>
                    <select class="form-control select2price" >
                        <option> </option>
                        <option>10 هزار</option>
                        <option>25 هزار</option>
                        <option>50 هزار</option>
                        <option>70 هزار</option>
                        <option>100 هزار</option>
                        <option>150 هزار</option>
                        <option>300 هزار</option>
                        <option>500 هزار</option>
                        <option>1 میلیون</option>
                        <option>2 میلیون</option>
                        <option>5 میلیون</option>
                        <option>10 میلیون</option>
                        <option>50 میلیون</option>
                        <option>100 میلیون</option>
                        <option>200 میلیون</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card mb-2  border-secondary      " >
            <div class="card-body">
                <div class=" clearfix">
                    <div   class="  float-left">فقط عکس‌دار</div>
                    <div   class="  float-right">
                        <input type="checkbox" data-toggle="toggle" data-on="  " data-off="  ">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-5  border-secondary      " >
            <div class="card-body">
                <div class=" clearfix">
                    <div   class="  float-left">فقط فوری‌ها</div>
                    <div   class="  float-right">
                        <input type="checkbox" data-toggle="toggle" data-on="  " data-off="  ">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
