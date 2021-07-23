@php
$start_page = $current_page > 3 ? $current_page - 2 : 1;
$end_page = $current_page + 2 < $number_of_pages ? $current_page + 2 : $number_of_pages;
@endphp
<ul class="cmn-box-tabMain01">
    <li>
        <div class="col02">
            <div>{{ $total_count }} records found</div>
        </div>
    </li>
    <li>
        <div class="col03">
            <div>
                <p>View</p>
                <ol>
                    <li><a href="#" class="{{ $item_per_page == 10 ? 'on' : '' }} item-per-page">10</a></li>
                    <li><a href="#" class="{{ $item_per_page == 30 ? 'on' : '' }} item-per-page">30</a></li>
                    <li><a href="#" class="{{ $item_per_page == 60 ? 'on' : '' }} item-per-page">60</a></li>
                </ol>
            </div>
        </div>
    </li>
    <li class="col04Wrap01">
        <div class="col04">
            <div>
                <ol>
                    <li class="prev" id="prev"><a class="{{ $current_page == 1 ? 'off' : '' }}"><span
                                aria-hidden="true">&laquo;</span></a></li>
                    @for ($i = $start_page; $i <= $end_page; $i++)
                        <li><a class="{{ $i == $current_page ? 'on' : '' }} page">{{ $i }}</a></li>
                    @endfor
                    <li class="next" id="next" last_page="{{ $number_of_pages }}"><a
                            class="{{ $current_page == $number_of_pages ? 'off' : '' }}" href="#"><span
                                aria-hidden="true">&raquo;</span></a></li>
                </ol>
            </div>
        </div>
    </li>
</ul>
<ul class="cmn-list-product01 type01 clearfix pt20 cmn-sec-imgHover01 ">
    @foreach ($items as $item)
        <li class="item-list" data-tracking_id="dmmref" data-content_id="ofje00321" data-product_id="ofje00321"
            data-price="1980">
            <p>{{ $item->name }}</p>
        </li>
    @endforeach
</ul>
