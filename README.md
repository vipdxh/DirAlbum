[README.md](https://github.com/user-attachments/files/23324477/README.md)
# DirAlbum - 目录直读相册系统

![DirAlbum Logo](https://via.placeholder.com/800x200?text=DirAlbum+Logo)

## 📖 项目描述 | Project Description
DirAlbum是一个轻量级、基于目录的相册系统，无需数据库，直接从目录读取图片文件。它会自动从子目录创建相册，并提供自适应的现代界面用于浏览照片。

DirAlbum is a lightweight, directory-based photo album system that directly reads image files from directories without requiring a database. It automatically creates albums from subdirectories and provides an adaptive, modern interface for browsing photos.

## ✨ 主要特性 | Key Features
- **目录直读**：自动从`photos`文件夹中的子目录创建相册  
  **Directory Direct-reading**: Automatically creates albums from subdirectories in the `photos` folder
- **自适应设计**：全响应式布局，支持手机、平板和桌面设备  
  **Adaptive Design**: Fully responsive layout works on mobile, tablet and desktop devices
- **现代UI**：简洁简约的设计，带有流畅的动画和过渡效果  
  **Modern UI**: Clean, minimalist design with smooth animations and transitions
- **轻量级**：无需数据库，文件结构简单  
  **Lightweight**: No database required, simple file structure
- **PHP兼容性**：支持PHP 5.6至8.2版本  
  **PHP Compatibility**: Supports PHP 5.6 to 8.2
- **图片预览**：点击查看带Lightbox效果的全尺寸图片  
  **Image Preview**: Click to view full-size images with Lightbox effect
- **自动缩略图**：自动使用每个目录中的第一张图片作为相册封面  
  **Auto Thumbnail**: Automatically uses the first image in each directory as album cover
- **空状态处理**：当没有相册或照片时显示友好提示  
  **Empty State Handling**: Friendly prompts when no albums or photos are found

## 🚀 安装与使用 | Installation & Usage
1. **克隆仓库**  
   ```bash
   git clone https://github.com/vipdxh/DirAlbum.git
   ```

2. **创建照片目录**  
   - 在项目根目录创建`photos`文件夹  
   - 在`photos`内添加子目录（每个子目录成为一个相册）  
   - 将照片放入这些子目录中  

   **Create photo directories**  
   - Create a `photos` folder in the project root  
   - Add subdirectories inside `photos` (each subdirectory becomes an album)  
   - Place your photos in these subdirectories

3. **配置Web服务器**  
   - 部署到任何支持PHP的Web服务器（Apache、Nginx等）  
   - 确保`photos`目录有正确的读取权限  

   **Configure web server**  
   - Deploy to any web server with PHP support (Apache, Nginx, etc.)  
   - Ensure the `photos` directory has proper read permissions

4. **访问相册**  
   打开浏览器并导航到项目URL  

   **Access the album**  
   Open your browser and navigate to the project URL

## 📁 项目结构 | Project Structure
```
DirAlbum/
├── index.php          # 主相册列表页面 | Main album list page
├── album.php          # 相册详情页面 | Album detail page
├── photos/            # 照片目录（需创建） | Photo directories (create this folder)
│   ├── Album1/        # 示例相册目录 | Example album directory
│   └── Album2/        # 另一个示例相册目录 | Another example album directory
├── default_cover.jpg  # 默认封面图片（可选） | Default cover image (optional)
└── README.md          # 项目文档 | Project documentation
```

## 📸 截图 | Screenshots


## 🛠️ 技术栈 | Technology Stack
- **前端**：HTML5、CSS3、JavaScript、Bootstrap 3  
  **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 3
- **后端**：PHP (5.6-8.2)  
  **Backend**: PHP (5.6-8.2)
- **库**：jQuery、Lightbox2  
  **Libraries**: jQuery, Lightbox2
- **字体**：Inter (Google Fonts)  
  **Fonts**: Inter (Google Fonts)

## 🤝 贡献指南 | Contribution
欢迎贡献！请遵循以下步骤：  
Contributions are welcome! Please follow these steps:
1. Fork本仓库 | Fork the repository
2. 创建新分支 | Create a new branch (`git checkout -b feature/your-feature`)
3. 提交更改 | Commit your changes (`git commit -am 'Add some feature'`)
4. 推送到分支 | Push to the branch (`git push origin feature/your-feature`)
5. 创建新的Pull Request | Create a new Pull Request

## 📄 许可证 | License
本项目采用MIT许可证 - 详见[LICENSE](LICENSE)文件  
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 作者 | Author
- vipdxh | vipdxh - [GitHub Profile](https://github.com/vipdxh)
- 邮箱 | Email: vipdxhl@gmail.com

## 🙏 致谢 | Acknowledgments
- Bootstrap 用于响应式设计 | Bootstrap for responsive design
- Lightbox2 用于图片预览功能 | Lightbox2 for image preview functionality
- Inter 字体来自Google Fonts | Inter font family from Google Fonts

---
使用❤️制作 | Made with ❤️ by [vipdxh]
