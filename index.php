<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

define('PHOTO_DIR', 'photos');
define('DEFAULT_COVER', 'default_cover.jpg');

// 检查照片目录是否存在
if (!is_dir(PHOTO_DIR)) {
    mkdir(PHOTO_DIR, 0755, true);
    echo "<div class='alert alert-warning'>照片目录不存在，已自动创建，请将照片放入photos目录下的子目录中。</div>";
}

// 获取所有相册目录
function get_albums() {
    $albums = [];
    $dirs = scandir(PHOTO_DIR);
    foreach ($dirs as $dir) {
        if ($dir == '.' || $dir == '..') continue;
        $full_path = PHOTO_DIR . '/' . $dir;
        if (is_dir($full_path)) {
            $albums[] = $dir;
        }
    }
    return $albums;
}

// 获取相册封面图
function get_album_cover($album) {
    $album_path = PHOTO_DIR . '/' . $album;
    $files = scandir($album_path);
    $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $image_extensions)) {
            return $album_path . '/' . $file;
        }
    }
    return DEFAULT_COVER;
}

// 截断标题
function truncate_title($title, $length = 20) {
    if (mb_strlen($title, 'UTF-8') > $length) {
        return mb_substr($title, 0, $length, 'UTF-8') . '...';
    }
    return $title;
}

$albums = get_albums();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DirAlbum - 目录直读相册系统</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* 全局样式 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e5e6 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* 标题样式 */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .page-header h1 {
            font-weight: 600;
            color: #2d3748;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: #718096;
            font-size: 1.1rem;
        }
        
        /* 相册网格 */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -15px;
            margin-right: -15px;
        }
        
        .album-item {
            padding: 0 15px;
            margin-bottom: 30px;
        }
        
        /* 相册卡片 */
        .album-card {
            display: block;
            height: 240px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            text-decoration: none !important;
            background-color: #ffffff;
        }
        
        .album-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }
        
        .album-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
            transition: transform 0.5s ease;
        }
        
        .album-card:hover .album-cover {
            transform: scale(1.05);
        }
        
        /* 标题样式 */
        .album-title {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: 500;
            color: #ffffff;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        
        /* 空状态样式 */
        .empty-state {
            text-align: center;
            padding: 50px 0;
            background-color: rgba(255,255,255,0.8);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .empty-state p {
            font-size: 1.2rem;
            color: #718096;
            margin-bottom: 20px;
        }
        
        .empty-state .btn {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        /* 响应式调整 */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .album-item {
                margin-bottom: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .album-card {
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>DirAlbum - 目录直读相册系统</h1>
            <p>浏览和管理您的照片集</p>
        </div>
        
        <div class="row">
            <?php if (empty($albums)): ?>
                <div class="col-md-12">
                    <div class="empty-state">
                        <p>暂无相册，请在photos目录下创建子目录并放入图片。</p>
                        <a href="#" class="btn btn-primary">了解如何添加照片</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($albums as $album): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6 album-item">
                        <div class="album-card">
                            <img src="<?php echo get_album_cover($album); ?>" alt="<?php echo $album; ?>" class="album-cover">
                            <div class="album-title"><?php echo truncate_title($album); ?></div>
                            <a href="album.php?album=<?php echo urlencode($album); ?>" class="stretched-link"></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
    <script>
        // 添加页面加载动画
        $(window).on('load', function() {
            // 可以在这里添加加载动画
        });
        
        // 添加图片懒加载
        $(document).ready(function() {
            $('.album-cover').each(function() {
                var img = $(this);
                var src = img.attr('src');
                img.attr('src', '');
                img.attr('data-src', src);
                
                // 简单的懒加载实现
                $(window).on('scroll resize', function() {
                    if (isInViewport(img)) {
                        img.attr('src', img.data('src'));
                        img.removeAttr('data-src');
                    }
                });
                
                // 初始检查
                if (isInViewport(img)) {
                    img.attr('src', img.data('src'));
                    img.removeAttr('data-src');
                }
            });
            
            function isInViewport(element) {
                var rect = element[0].getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            }
        });
    </script>
</body>
</html>