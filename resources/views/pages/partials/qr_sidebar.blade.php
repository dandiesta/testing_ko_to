<div class="list-group">
    @if (true)
    <div class="list-group-item">
        <ul class="nav nav-pills nav-stacked">
            <li @if ($current_page == 'edit_package') class="active" @endif>
                <a href="{{ route('edit_package', ['id' => $app->id]) }}"><i class="fa fa-pencil"></i> Edit</a>
            </li>
            <li @if ($current_page == 'delete_confirm') class="active" @endif>
                <a href="{{ route('delete_confirm', ['id' => $app->id]) }}"><i class="fa fa-trash-o"></i> Delete</a>
            </li>
        </ul>
    </div>
    @endif
    <div class="list-group-item">
        <div class="text-center">
            <p>Scan this to get the link</p>
                <img src="https://chart.googleapis.com/chart?chs=150&cht=qr&chl={{ route('install_package', ['id'=>$app->id]) }}">
        </div>
    </div>
</div>
