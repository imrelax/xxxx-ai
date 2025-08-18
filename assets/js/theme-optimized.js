/**
 * 优化的主题JavaScript文件
 * 减少DOM操作，提升性能
 */

(function($) {
    'use strict';
    
    // 缓存常用的DOM元素
    const $window = $(window);
    const $document = $(document);
    const $body = $('body');
    
    // 性能优化的工具函数
    const Utils = {
        // 防抖函数
        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },
        
        // 节流函数
        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },
        
        // 检查元素是否在视口中
        isInViewport: function(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    };
    
    // 幻灯片功能（优化版）
    const SlideShow = {
        init: function() {
            const $slideContainer = $('.slide-container');
            if ($slideContainer.length === 0) return;
            
            this.$container = $slideContainer;
            this.$slides = $slideContainer.find('.slide-item');
            this.$indicators = $slideContainer.find('.slide-indicator');
            this.currentIndex = 0;
            this.slideCount = this.$slides.length;
            this.autoPlayInterval = null;
            
            if (this.slideCount <= 1) return;
            
            this.bindEvents();
            this.startAutoPlay();
        },
        
        bindEvents: function() {
            const self = this;
            
            // 指示器点击事件
            this.$indicators.on('click', function(e) {
                e.preventDefault();
                const index = $(this).index();
                self.goToSlide(index);
            });
            
            // 鼠标悬停暂停自动播放
            this.$container.on('mouseenter', () => this.stopAutoPlay())
                          .on('mouseleave', () => this.startAutoPlay());
        },
        
        goToSlide: function(index) {
            if (index === this.currentIndex) return;
            
            this.$slides.eq(this.currentIndex).removeClass('active');
            this.$indicators.eq(this.currentIndex).removeClass('active');
            
            this.currentIndex = index;
            
            this.$slides.eq(this.currentIndex).addClass('active');
            this.$indicators.eq(this.currentIndex).addClass('active');
        },
        
        nextSlide: function() {
            const nextIndex = (this.currentIndex + 1) % this.slideCount;
            this.goToSlide(nextIndex);
        },
        
        startAutoPlay: function() {
            if (this.autoPlayInterval) return;
            this.autoPlayInterval = setInterval(() => this.nextSlide(), 5000);
        },
        
        stopAutoPlay: function() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.autoPlayInterval = null;
            }
        }
    };
    
    // 图片懒加载（优化版）
    const LazyLoad = {
        init: function() {
            this.images = document.querySelectorAll('img[data-src]');
            if (this.images.length === 0) return;
            
            if ('IntersectionObserver' in window) {
                this.initIntersectionObserver();
            } else {
                this.initScrollListener();
            }
        },
        
        initIntersectionObserver: function() {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });
            
            this.images.forEach(img => imageObserver.observe(img));
        },
        
        initScrollListener: function() {
            const checkImages = Utils.throttle(() => {
                this.images.forEach((img, index) => {
                    if (Utils.isInViewport(img)) {
                        this.loadImage(img);
                        this.images.splice(index, 1);
                    }
                });
            }, 100);
            
            $window.on('scroll resize', checkImages);
            checkImages(); // 初始检查
        },
        
        loadImage: function(img) {
            const $img = $(img);
            const src = $img.data('src');
            
            if (!src) return;
            
            // 创建新图片对象预加载
            const newImg = new Image();
            newImg.onload = function() {
                $img.attr('src', src)
                    .removeClass('lazy-loading')
                    .addClass('lazy-loaded');
            };
            newImg.onerror = function() {
                $img.addClass('lazy-error');
            };
            newImg.src = src;
        }
    };
    
    // 搜索功能增强（优化版）
    const SearchEnhancer = {
        init: function() {
            this.$searchForm = $('.search-form');
            this.$searchInput = this.$searchForm.find('input[type="search"]');
            
            if (this.$searchInput.length === 0) return;
            
            this.bindEvents();
        },
        
        bindEvents: function() {
            // 防抖搜索建议
            const debouncedSearch = Utils.debounce((query) => {
                if (query.length >= 2) {
                    this.showSearchSuggestions(query);
                } else {
                    this.hideSearchSuggestions();
                }
            }, 300);
            
            this.$searchInput.on('input', function() {
                debouncedSearch($(this).val().trim());
            });
            
            // 点击外部隐藏建议
            $document.on('click', (e) => {
                if (!$(e.target).closest('.search-form').length) {
                    this.hideSearchSuggestions();
                }
            });
        },
        
        showSearchSuggestions: function(query) {
            // 这里可以添加AJAX搜索建议功能
            console.log('搜索建议:', query);
        },
        
        hideSearchSuggestions: function() {
            $('.search-suggestions').hide();
        }
    };
    
    // 移动端菜单（优化版）
    const MobileMenu = {
        init: function() {
            this.$menuToggle = $('.mobile-menu-toggle');
            this.$mobileMenu = $('.mobile-menu');
            
            if (this.$menuToggle.length === 0) return;
            
            this.isOpen = false;
            this.bindEvents();
        },
        
        bindEvents: function() {
            this.$menuToggle.on('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
            
            // ESC键关闭菜单
            $document.on('keydown', (e) => {
                if (e.keyCode === 27 && this.isOpen) {
                    this.close();
                }
            });
            
            // 点击遮罩关闭菜单
            this.$mobileMenu.on('click', (e) => {
                if (e.target === this.$mobileMenu[0]) {
                    this.close();
                }
            });
        },
        
        toggle: function() {
            this.isOpen ? this.close() : this.open();
        },
        
        open: function() {
            this.isOpen = true;
            $body.addClass('mobile-menu-open');
            this.$mobileMenu.addClass('active');
        },
        
        close: function() {
            this.isOpen = false;
            $body.removeClass('mobile-menu-open');
            this.$mobileMenu.removeClass('active');
        }
    };
    
    // 平滑滚动（优化版）
    const SmoothScroll = {
        init: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 600, 'easeInOutQuart');
                }
            });
        }
    };
    
    // 页面加载进度条
    const LoadingProgress = {
        init: function() {
            this.createProgressBar();
            this.bindEvents();
        },
        
        createProgressBar: function() {
            if ($('#loading-progress').length) return;
            
            $body.prepend('<div id="loading-progress"><div class="progress-bar"></div></div>');
            this.$progress = $('#loading-progress');
            this.$bar = this.$progress.find('.progress-bar');
        },
        
        bindEvents: function() {
            $window.on('beforeunload', () => {
                this.show();
            });
            
            $window.on('load', () => {
                this.hide();
            });
        },
        
        show: function() {
            this.$progress.addClass('active');
        },
        
        hide: function() {
            this.$progress.removeClass('active');
            setTimeout(() => {
                this.$progress.hide();
            }, 500);
        }
    };
    
    // Markdown解析（优化版）
    const MarkdownParser = {
        init: function() {
            if (typeof marked === 'undefined') return;
            
            // 配置marked选项
            marked.setOptions({
                highlight: function(code, lang) {
                    // 简单的代码高亮
                    return '<code class="language-' + (lang || 'text') + '">' + 
                           code.replace(/</g, '&lt;').replace(/>/g, '&gt;') + 
                           '</code>';
                },
                breaks: true,
                gfm: true
            });
            
            this.parseMarkdownContent();
        },
        
        parseMarkdownContent: function() {
            $('.markdown-content').each(function() {
                const $this = $(this);
                const markdownText = $this.text();
                if (markdownText.trim()) {
                    const htmlContent = marked(markdownText);
                    $this.html(htmlContent).addClass('parsed');
                }
            });
        }
    };
    
    // 主题初始化
    const ThemeInit = {
        init: function() {
            // 按优先级初始化各模块
            LoadingProgress.init();
            LazyLoad.init();
            SlideShow.init();
            SearchEnhancer.init();
            MobileMenu.init();
            SmoothScroll.init();
            MarkdownParser.init();
            
            // 添加主题样式
            this.addThemeStyles();
            
            console.log('XMan主题优化版本初始化完成');
        },
        
        addThemeStyles: function() {
            const styles = `
                <style id="xman-dynamic-styles">
                /* 加载进度条样式 */
                #loading-progress {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 3px;
                    background: rgba(0,0,0,0.1);
                    z-index: 9999;
                    opacity: 0;
                    transition: opacity 0.3s;
                }
                #loading-progress.active {
                    opacity: 1;
                }
                #loading-progress .progress-bar {
                    height: 100%;
                    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
                    width: 0;
                    animation: loading 2s ease-in-out infinite;
                }
                @keyframes loading {
                    0% { width: 0%; }
                    50% { width: 70%; }
                    100% { width: 100%; }
                }
                
                /* 懒加载图片样式 */
                img[data-src] {
                    opacity: 0;
                    transition: opacity 0.3s;
                }
                img.lazy-loaded {
                    opacity: 1;
                }
                img.lazy-error {
                    opacity: 0.5;
                    background: #f0f0f0;
                }
                
                /* 移动端菜单优化 */
                .mobile-menu {
                    transform: translateX(-100%);
                    transition: transform 0.3s ease;
                }
                .mobile-menu.active {
                    transform: translateX(0);
                }
                body.mobile-menu-open {
                    overflow: hidden;
                }
                
                /* 搜索框增强样式 */
                .search-form {
                    position: relative;
                }
                .search-suggestions {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: white;
                    border: 1px solid #ddd;
                    border-top: none;
                    max-height: 200px;
                    overflow-y: auto;
                    z-index: 1000;
                }
                </style>
            `;
            
            if (!$('#xman-dynamic-styles').length) {
                $('head').append(styles);
            }
        }
    };
    
    // 文档就绪时初始化
    $document.ready(function() {
        ThemeInit.init();
    });
    
})(jQuery);