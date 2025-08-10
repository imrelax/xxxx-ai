/**
 * X-Man AI主题 - JavaScript文件
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('X-Man AI主题已加载');
    
    // 初始化所有功能
    initSlider();
    initBackToTop();
    initSmoothScroll();
    initSearchEnhancement();
    initImageLazyLoad();
    initMarkdownParser();
    
    /**
     * 幻灯片功能
     */
    function initSlider() {
        const slides = document.querySelectorAll('[data-slide]');
        const dots = document.querySelectorAll('.absolute.bottom-4 button[data-slide]');
        const prevBtn = document.querySelector('#prevSlide');
        const nextBtn = document.querySelector('#nextSlide');
        
        if (slides.length === 0) return;
        
        let currentSlide = 1; // 从1开始，匹配HTML中的data-slide
        let slideInterval;
        
        function showSlide(slideNumber) {
            // 隐藏所有幻灯片
            slides.forEach(slide => {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            });
            
            // 显示当前幻灯片（通过data-slide属性查找）
            const targetSlide = document.querySelector(`[data-slide="${slideNumber}"]`);
            if (targetSlide) {
                targetSlide.classList.remove('opacity-0');
                targetSlide.classList.add('opacity-100');
            }
            
            // 更新指示点
            dots.forEach(dot => {
                const dotSlideNumber = parseInt(dot.getAttribute('data-slide'));
                if (dotSlideNumber === slideNumber) {
                    dot.classList.remove('bg-white/50');
                    dot.classList.add('bg-white');
                } else {
                    dot.classList.remove('bg-white');
                    dot.classList.add('bg-white/50');
                }
            });
        }
        
        function nextSlide() {
            currentSlide = currentSlide >= slides.length ? 1 : currentSlide + 1;
            showSlide(currentSlide);
        }
        
        function prevSlide() {
            currentSlide = currentSlide <= 1 ? slides.length : currentSlide - 1;
            showSlide(currentSlide);
        }
        
        function startAutoPlay() {
            slideInterval = setInterval(nextSlide, 5000);
        }
        
        function stopAutoPlay() {
            clearInterval(slideInterval);
        }
        
        // 按钮事件
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                stopAutoPlay();
                nextSlide();
                startAutoPlay();
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                stopAutoPlay();
                prevSlide();
                startAutoPlay();
            });
        }
        
        // 指示点事件
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                stopAutoPlay();
                currentSlide = parseInt(this.getAttribute('data-slide'));
                showSlide(currentSlide);
                startAutoPlay();
            });
        });
        
        // 初始化显示第一张幻灯片
        showSlide(1);
        
        // 鼠标悬停暂停自动播放
        const sliderContainer = document.querySelector('section.relative.mb-12');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', stopAutoPlay);
            sliderContainer.addEventListener('mouseleave', startAutoPlay);
        }
        
        // 启动自动播放
        startAutoPlay();
    }
    
    /**
     * 返回顶部功能
     */
    function initBackToTop() {
        const backToTopBtn = document.getElementById('back-to-top');
        if (!backToTopBtn) return;
        
        // 显示/隐藏返回顶部按钮
        function toggleBackToTop() {
            if (window.pageYOffset > 300) {
                backToTopBtn.style.display = 'flex';
                backToTopBtn.style.opacity = '1';
            } else {
                backToTopBtn.style.opacity = '0';
                setTimeout(() => {
                    if (window.pageYOffset <= 300) {
                        backToTopBtn.style.display = 'none';
                    }
                }, 300);
            }
        }
        
        // 滚动事件监听
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    toggleBackToTop();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        // 点击返回顶部
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    /**
     * 平滑滚动
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                // 只处理真正的锚点链接，跳过空链接和其他类型的链接
                if (href === '#' || href.length <= 1) return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    /**
     * 搜索增强
     */
    function initSearchEnhancement() {
        const searchInput = document.querySelector('.search-input');
        if (!searchInput) return;
        
        // 搜索建议功能（可以根据需要扩展）
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length > 2) {
                searchTimeout = setTimeout(() => {
                    // 这里可以添加搜索建议的AJAX请求
                    console.log('搜索建议:', query);
                }, 300);
            }
        });
        
        // 搜索框焦点效果
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('search-focused');
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('search-focused');
        });
    }
    
    /**
     * 图片懒加载
     */
    function initImageLazyLoad() {
        const images = document.querySelectorAll('img[data-src]');
        if (images.length === 0) return;
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => {
            img.classList.add('lazy');
            imageObserver.observe(img);
        });
    }
    
    /**
     * 文章卡片悬停效果增强
     */
    function initCardHoverEffects() {
        const cards = document.querySelectorAll('.post-card, .link-card, .related-post');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }
    
    // 初始化卡片悬停效果
    initCardHoverEffects();
    
    /**
     * 导航菜单移动端优化
     */
    function initMobileMenu() {
        const nav = document.querySelector('.nav');
        if (!nav) return;
        
        // 创建移动端菜单按钮
        const mobileMenuBtn = document.createElement('button');
        mobileMenuBtn.className = 'mobile-menu-btn';
        mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
        mobileMenuBtn.style.display = 'none';
        
        // 插入到导航中
        nav.appendChild(mobileMenuBtn);
        
        // 移动端菜单切换
        mobileMenuBtn.addEventListener('click', function() {
            const navRight = document.querySelector('.nav-right');
            if (navRight) {
                navRight.classList.toggle('mobile-menu-open');
                const icon = this.querySelector('i');
                icon.className = navRight.classList.contains('mobile-menu-open') 
                    ? 'fas fa-times' 
                    : 'fas fa-bars';
            }
        });
        
        // 响应式显示/隐藏
        function checkMobileMenu() {
            if (window.innerWidth <= 768) {
                mobileMenuBtn.style.display = 'block';
            } else {
                mobileMenuBtn.style.display = 'none';
                const navRight = document.querySelector('.nav-right');
                if (navRight) {
                    navRight.classList.remove('mobile-menu-open');
                }
            }
        }
        
        window.addEventListener('resize', checkMobileMenu);
        checkMobileMenu();
    }
    
    // 初始化移动端菜单
    initMobileMenu();
    
    /**
     * 页面加载进度条
     */
    function initLoadingProgress() {
        // 创建进度条
        const progressBar = document.createElement('div');
        progressBar.className = 'loading-progress';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #0969da, #1a7f37);
            z-index: 9999;
            transition: width 0.3s ease;
        `;
        document.body.appendChild(progressBar);
        
        // 模拟加载进度
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                setTimeout(() => {
                    progressBar.style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(progressBar);
                    }, 300);
                }, 200);
            }
            progressBar.style.width = progress + '%';
        }, 100);
    }
    
    // 如果页面还在加载，显示进度条
    if (document.readyState === 'loading') {
        initLoadingProgress();
    }
    
    /**
     * 添加CSS样式
     */
    function addCustomStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .search-focused {
                transform: scale(1.02);
                transition: transform 0.2s ease;
            }
            
            .lazy {
                opacity: 0;
                transition: opacity 0.3s;
            }
            
            .loaded {
                opacity: 1;
            }
            
            .mobile-menu-btn {
                background: none;
                border: none;
                color: #24292f;
                font-size: 18px;
                cursor: pointer;
                padding: 8px;
                border-radius: 4px;
                transition: background 0.2s;
            }
            
            .mobile-menu-btn:hover {
                background: rgba(0, 0, 0, 0.1);
            }
            
            @media (max-width: 768px) {
                .nav-right {
                    position: absolute;
                    top: 100%;
                    right: 0;
                    background: white;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    border-radius: 8px;
                    padding: 16px;
                    display: none;
                    flex-direction: column;
                    gap: 12px;
                    min-width: 200px;
                }
                
                .nav-right.mobile-menu-open {
                    display: flex;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    // 添加自定义样式
    addCustomStyles();
    
    // 添加平滑滚动效果
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    /**
     * Markdown解析功能
     */
    function initMarkdownParser() {
        // 检查marked库是否已加载
        if (typeof marked === 'undefined') {
            console.warn('Marked.js库未加载，Markdown解析功能不可用');
            return;
        }
        
        // 配置marked选项
        marked.setOptions({
            breaks: true,        // 支持换行
            gfm: true,          // 支持GitHub风格的Markdown
            sanitize: false,    // 不过滤HTML（WordPress已经处理了安全性）
            smartLists: true,   // 智能列表
            smartypants: true   // 智能标点
        });
        
        // 查找标记为需要Markdown解析的内容区域
        const markdownElements = document.querySelectorAll('[data-markdown="true"]');
        
        markdownElements.forEach(element => {
            // 获取纯文本内容（去除HTML标签）
            const textContent = element.textContent || element.innerText;
            
            // 解析Markdown并替换内容
            try {
                const parsedContent = marked.parse(textContent);
                element.innerHTML = parsedContent;
                
                // 添加Markdown样式类
                element.classList.add('markdown-content');
                
                console.log('Markdown内容已解析');
            } catch (error) {
                console.error('Markdown解析错误:', error);
            }
        });
        
        // 如果没有找到标记的元素，则尝试自动检测
        if (markdownElements.length === 0) {
            const contentElements = document.querySelectorAll('.entry-content, .post-content, .article-content, .content');
            
            contentElements.forEach(element => {
                const content = element.innerHTML.trim();
                
                // 检测是否包含Markdown语法
                if (isMarkdownContent(content)) {
                    // 获取纯文本内容（去除HTML标签）
                    const textContent = element.textContent || element.innerText;
                    
                    // 解析Markdown并替换内容
                    try {
                        const parsedContent = marked.parse(textContent);
                        element.innerHTML = parsedContent;
                        
                        // 添加Markdown样式类
                        element.classList.add('markdown-content');
                        
                        console.log('自动检测到Markdown内容并已解析');
                    } catch (error) {
                        console.error('Markdown解析错误:', error);
                    }
                }
            });
        }
        
        // 添加Markdown样式
        addMarkdownStyles();
    }
    
    /**
     * 检测内容是否包含Markdown语法
     */
    function isMarkdownContent(content) {
        const markdownPatterns = [
            /#{1,6}\s+/,           // 标题
            /\*\*.*?\*\*/,         // 粗体
            /\*.*?\*/,             // 斜体
            /`.*?`/,               // 行内代码
            /```[\s\S]*?```/,      // 代码块
            /^\s*[-*+]\s+/m,       // 无序列表
            /^\s*\d+\.\s+/m,       // 有序列表
            /\[.*?\]\(.*?\)/,      // 链接
            /!\[.*?\]\(.*?\)/,     // 图片
            /^\s*>\s+/m,           // 引用
            /^\s*\|.*\|\s*$/m,     // 表格
            /^\s*---+\s*$/m        // 分隔线
        ];
        
        return markdownPatterns.some(pattern => pattern.test(content));
    }
    
    /**
     * 添加Markdown样式
     */
    function addMarkdownStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .markdown-content {
                line-height: 1.6;
                color: #333;
            }
            
            .markdown-content h1,
            .markdown-content h2,
            .markdown-content h3,
            .markdown-content h4,
            .markdown-content h5,
            .markdown-content h6 {
                margin: 1.5em 0 0.5em 0;
                font-weight: bold;
                line-height: 1.2;
            }
            
            .markdown-content h1 { font-size: 2em; }
            .markdown-content h2 { font-size: 1.5em; }
            .markdown-content h3 { font-size: 1.3em; }
            .markdown-content h4 { font-size: 1.1em; }
            .markdown-content h5 { font-size: 1em; }
            .markdown-content h6 { font-size: 0.9em; }
            
            .markdown-content p {
                margin: 1em 0;
            }
            
            .markdown-content strong {
                font-weight: bold;
            }
            
            .markdown-content em {
                font-style: italic;
            }
            
            .markdown-content code {
                background-color: #f4f4f4;
                padding: 2px 4px;
                border-radius: 3px;
                font-family: 'Courier New', monospace;
                font-size: 0.9em;
            }
            
            .markdown-content pre {
                background-color: #f8f8f8;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 1em;
                overflow-x: auto;
                margin: 1em 0;
            }
            
            .markdown-content pre code {
                background: none;
                padding: 0;
                border-radius: 0;
            }
            
            .markdown-content ul,
            .markdown-content ol {
                margin: 1em 0;
                padding-left: 2em;
            }
            
            .markdown-content li {
                margin: 0.5em 0;
            }
            
            .markdown-content blockquote {
                border-left: 4px solid #ddd;
                margin: 1em 0;
                padding: 0.5em 1em;
                background-color: #f9f9f9;
                font-style: italic;
            }
            
            .markdown-content table {
                border-collapse: collapse;
                width: 100%;
                margin: 1em 0;
            }
            
            .markdown-content th,
            .markdown-content td {
                border: 1px solid #ddd;
                padding: 8px 12px;
                text-align: left;
            }
            
            .markdown-content th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
            
            .markdown-content hr {
                border: none;
                border-top: 2px solid #ddd;
                margin: 2em 0;
            }
            
            .markdown-content a {
                color: #007cba;
                text-decoration: none;
            }
            
            .markdown-content a:hover {
                text-decoration: underline;
            }
            
            .markdown-content img {
                max-width: 100%;
                height: auto;
                margin: 1em 0;
            }
        `;
        document.head.appendChild(style);
    }
    
    console.log('X-Man AI主题所有功能初始化完成');
});