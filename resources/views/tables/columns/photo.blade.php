<div class="photo">
    <img src={{Storage::url($getRecord()->photo)}} alt={{$getRecord()->photo}} >
</div>

@push('styles')
<style>
    .photo {
        padding: 5px
    }

    .photo img{
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
</style>
@endpush