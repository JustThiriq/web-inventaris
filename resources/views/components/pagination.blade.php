@if ($pagination->hasPages())
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="datatable-info">
                Menampilkan {{ $pagination->firstItem() ?? 0 }} - {{ $pagination->lastItem() ?? 0 }}
                dari {{ $pagination->total() }} total data
            </div>
        </div>
        <div class="col-md-6">
            <div class="float-right">
                {!! $pagination->appends(request()->query())->links() !!}
            </div>
        </div>
    </div>
@else
    @if ($pagination->count() > 0)
        <div class="text-muted mt-3">
            Total {{ $pagination->count() }} data
        </div>
    @endif
@endif
