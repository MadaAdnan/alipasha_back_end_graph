<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار مكون زر المشاركة</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">اختبار مكون زر المشاركة</h4>
                    </div>
                    <div class="card-body">
                        <h5>{{ $title }}</h5>
                        <p class="text-muted">{{ $description }}</p>
                        
                        <div class="mt-4">
                            <h6>زر المشاركة الافتراضي:</h6>
                            <x-components.share-btn-component />
                        </div>
                        
                        <div class="mt-4">
                            <h6>زر المشاركة مع معلمات:</h6>
                            <x-components.share-btn-component 
                                :url="request()->url()" 
                                title="عنوان تجريبي" 
                                description="وصف تجريبي للاختبار"
                                :showLabel="true"
                                :platforms="['facebook', 'twitter', 'whatsapp', 'telegram', 'email']"
                            />
                        </div>
                        
                        <div class="mt-4">
                            <h6>زر المشاركة بدون تسمية:</h6>
                            <x-components.share-btn-component 
                                :url="request()->url()" 
                                :showLabel="false"
                                :platforms="['facebook', 'twitter', 'linkedin']"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Load any pushed styles and scripts -->
    @stack('styles')
    @stack('js')
</body>
</html>