<div id="carouselExampleCaptions" class="carousel slide m-auto position-relative"
    data-bs-ride="carousel" style="max-width: 600px;">
    <div class="carousel-indicators">
        @foreach ($images as $index => $image)
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}"
            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
            aria-label="Slide {{ $index + 1 }}">
        </button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @foreach ($images as $index => $image)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            <img src="{{ Storage::url($image['url']) }}" class="d-block w-100" alt="{{ $image['title'] }}"
                style="height: 400px; max-height: 80vw; object-fit: contain;">
            <div class="carousel-caption-container">
                <div class="carousel-caption d-none d-md-block py-3" style="background-color: rgba(0, 0, 0, 0);">
                    <h5 class="text-emphasis-color" style="color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);">{{ $image['title'] }}</h5>
                    <p class="text-emphasis-color" style="color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);">{{ $image['description'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
        data-bs-slide="prev" style="width: 10%; color: black;">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5);"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
        data-bs-slide="next" style="width: 10%; color: black;">
        <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0, 0, 0, 0.5);"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>