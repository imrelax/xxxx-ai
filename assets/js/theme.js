/**
 * X-Man AI 主题默认JavaScript功能
 * 包含主题核心功能，适用于所有页面
 * 
 * @package X-Man AI Theme
 * @author xxxx.im
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('X-Man AI主题核心功能开始初始化...');
    
    // 初始化所有主题功能
    initSlider();
    // initBackToTop(); // 已移除重复的返回顶部按钮
    initSmoothScroll();
    initSearchEnhancement();
    initImageLazyLoad();
    initCardHoverEffects();
    // initMobileMenu(); // 已在header.php中实现，避免重复
    initLoadingProgress();
    addCustomStyles();
    initMarkdownParser();
    
    /**
     * 幻灯片功能
     */
    function initSlider() {
        const slider = document.querySelector('section.relative.mb-12');
        if (!slider) {
            console.log('幻灯片容器未找到');
            return;
        }
        
        const slides = slider.querySelectorAll('.slider-slide');
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');
        const indicators = slider.querySelector('.absolute.bottom-4');
        
        console.log('找到幻灯片数量:', slides.length);
        
        if (slides.length === 0) {
            console.log('没有找到幻灯片');
            return;
        }
        
        let currentSlide = 0;
        let autoPlayInterval;
        
        // 绑定现有指示点
        if (indicators) {
            const indicatorButtons = indicators.querySelectorAll('button');
            indicatorButtons.forEach((indicator, index) => {
                indicator.addEventListener('click', () => goToSlide(index));
            });
        }
        
        // 显示指定幻灯片
        function showSlide(index) {
            console.log('显示幻灯片:', index);
            
            // 确保索引在有效范围内
            if (index < 0 || index >= slides.length) {
                console.error('无效的幻灯片索引:', index);
                return;
            }
            
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.remove('opacity-0');
                    slide.classList.add('opacity-100');
                    slide.style.zIndex = '10';
                } else {
                    slide.classList.remove('opacity-100');
                    slide.classList.add('opacity-0');
                    slide.style.zIndex = '1';
                }
            });
            
            // 更新指示点
            const indicatorElements = indicators?.querySelectorAll('button');
            indicatorElements?.forEach((indicator, i) => {
                if (i === index) {
                    indicator.classList.remove('bg-white/50');
                    indicator.classList.add('bg-white');
                } else {
                    indicator.classList.remove('bg-white');
                    indicator.classList.add('bg-white/50');
                }
            });
        }
        
        // 跳转到指定幻灯片
        function goToSlide(index) {
            console.log('跳转到幻灯片:', index);
            currentSlide = index;
            showSlide(currentSlide);
        }
        
        // 下一张
        function nextSlide() {
            const nextIndex = (currentSlide + 1) % slides.length;
            console.log('下一张幻灯片:', currentSlide, '->', nextIndex);
            currentSlide = nextIndex;
            showSlide(currentSlide);
        }
        
        // 上一张
        function prevSlide() {
            const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
            console.log('上一张幻灯片:', currentSlide, '->', prevIndex);
            currentSlide = prevIndex;
            showSlide(currentSlide);
        }
        
        // 绑定按钮事件
        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('点击下一张按钮');
                nextSlide();
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('点击上一张按钮');
                prevSlide();
            });
        }
        
        // 自动播放
        function startAutoPlay() {
            autoPlayInterval = setInterval(() => {
                console.log('自动播放下一张');
                nextSlide();
            }, 5000);
        }
        
        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
                autoPlayInterval = null;
            }
        }
        
        // 鼠标悬停暂停自动播放
        slider.addEventListener('mouseenter', stopAutoPlay);
        slider.addEventListener('mouseleave', startAutoPlay);
        
        // 初始化
        console.log('初始化幻灯片');
        showSlide(0);
        startAutoPlay();
    }
    
    // 返回顶部功能已移除，避免与footer.php中的按钮重复
    
    /**
     * 平滑滚动功能
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                // 跳过空的#或只有#的链接
                if (href === '#' || href.length <= 1) {
                    return;
                }
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
    
    /**
     * 搜索增强功能
     */
    function initSearchEnhancement() {
        const searchInputs = document.querySelectorAll('input[type="search"], .search-input');
        
        searchInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('search-focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('search-focused');
            });
        });
    }
    
    /**
     * 图片懒加载功能
     */
    function initImageLazyLoad() {
        const images = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        img.classList.add('loaded');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => {
                img.classList.add('lazy');
                imageObserver.observe(img);
            });
        } else {
            // 降级处理
            images.forEach(img => {
                img.src = img.dataset.src;
                img.classList.add('loaded');
            });
        }
    }
    
    /**
     * 文章卡片悬停效果增强
     */
    function initCardHoverEffects() {
        const cards = document.querySelectorAll('.post-card, .article-card, .card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
                this.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            });
        });
    }
    
    /**
     * 移动端菜单功能 - 已移至header.php统一管理
     * 此函数保留为空以避免冲突
     */
    function initMobileMenu() {
        // 移动端菜单功能已在header.php中实现
        // 避免重复初始化
        return;
    }
    
    /**
     * 页面加载进度条
     */
    function initLoadingProgress() {
        // 如果页面还在加载，显示进度条
        if (document.readyState === 'loading') {
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
            sanitize: true,     // 过滤HTML以防止XSS攻击
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