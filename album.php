<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

define('PHOTO_DIR', 'photos');
$album = isset($_GET['album']) ? basename($_GET['album']) : '';
$album_path = PHOTO_DIR . '/' . $album;

if (empty($album) || !is_dir($album_path)) {
    header('Location: index.php');
    exit;
}

// 获取相册中的所有图片
function get_album_images($album_path) {
    $images = [];
    $files = scandir($album_path);
    $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $image_extensions)) {
            $images[] = $album_path . '/' . $file;
        }
    }
    return $images;
}

// 截断标题
function truncate_title($title, $length = 20) {
    if (mb_strlen($title, 'UTF-8') > $length) {
        return mb_substr($title, 0, $length, 'UTF-8') . '...';
    }
    return $title;
}

$images = get_album_images($album_path);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DirAlbum - 目录直读相册系统 - <?php echo $album; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css">
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
        
        /* 返回链接 */
        .back-link {
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .back-link a {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #2d3748;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 8px;
            background-color: rgba(255,255,255,0.8);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .back-link a:hover {
            background-color: #ffffff;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        .back-link a i {
            margin-right: 8px;
        }
        
        .back-link h2 {
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }
        
        /* 图片网格 */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -15px;
            margin-right: -15px;
        }
        
        .image-item {
            padding: 0 15px;
            margin-bottom: 30px;
        }
        
        /* 图片卡片 */
        .image-card {
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
        
        .image-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }
        
        .image-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
            transition: transform 0.5s ease;
        }
        
        .image-card:hover .image-cover {
            transform: scale(1.05);
        }
        
        /* 图片标题 */
        .image-title {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px 15px;
            font-size: 14px;
            color: #ffffff;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
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
        
        /* 响应式调整 */
        @media (max-width: 768px) {
            .back-link {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .back-link h2 {
                margin-top: 15px;
            }
            
            .image-card {
                height: 200px;
            }
        }
        
        @media (max-width: 576px) {
            .image-card {
                height: 180px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link">
            <a href="index.php">
                <i class="glyphicon glyphicon-arrow-left"></i> 返回相册列表
            </a>
            <h2><?php echo $album; ?></h2>
        </div>
        
        <div class="row">
            <?php if (empty($images)): ?>
                <div class="col-md-12">
                    <div class="empty-state">
                        <p>该相册暂无图片。</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($images as $image): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6 image-item">
                        <div class="image-card">
                            <img src="<?php echo $image; ?>" alt="<?php echo basename($image); ?>" class="image-cover">
                            <div class="image-title"><?php echo truncate_title(basename($image)); ?></div>
                            <a href="<?php echo $image; ?>" data-lightbox="album" data-title="<?php echo basename($image); ?>" class="stretched-link"></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/js/lightbox.min.js"></script>
    <script>
        // 添加页面加载动画
        $(window).on('load', function() {
            // 可以在这里添加加载动画
        });
        
        // 添加图片懒加载
        $(document).ready(function() {
            $('.image-cover').each(function() {
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