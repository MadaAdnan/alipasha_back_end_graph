@props([
    'store'=>null
])
{{dd($store->social)}}
<div class="social-media-section text-center py-4">
    <h5 class="mb-3">تابعنا على</h5>
    <div class="social-links d-flex justify-content-center gap-3 flex-wrap">
        @if($store->social['face']=='')
            <a href="https://facebook.com/yourpage" target="_blank"
               class="social-link facebook rounded-circle d-flex align-items-center justify-content-center"
               title="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
        @endif
        @if($store->social['twitter']=='')
            <a href="https://twitter.com/yourprofile" target="_blank"
               class="social-link twitter rounded-circle d-flex align-items-center justify-content-center"
               title="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
        @endif
        @if($store->social['instagram']=='')
            <a href="https://instagram.com/yourprofile" target="_blank"
               class="social-link instagram rounded-circle d-flex align-items-center justify-content-center"
               title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
        @endif
        @if($store->social['linkedin']=='')
            <a href="https://linkedin.com/in/yourprofile" target="_blank"
               class="social-link linkedin rounded-circle d-flex align-items-center justify-content-center"
               title="LinkedIn">
                <i class="fab fa-linkedin-in"></i>
            </a>
        @endif
        @if($store->social['tiktok']=='')
            <a href="https://youtube.com/c/yourchannel" target="_blank"
               class="social-link youtube rounded-circle d-flex align-items-center justify-content-center"
               title="YouTube">
                <i class="fab fa-tiktok"></i>
            </a>
        @endif
    </div>
    <p class="text-muted mt-3 mb-0">

    </p>
</div>

