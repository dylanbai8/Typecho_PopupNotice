<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * PopupNotice 插件
 *
 * @package PopupNotice
 * @version 1.0
 * @author chatgpt
 * @link https://github.com/dylanbai8
 */

class PopupNotice_Plugin implements Typecho_Plugin_Interface
{
    // 激活插件方法
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('PopupNotice_Plugin', 'header');
        return _t('插件已激活，设置公告内容以便生效。');
    }

    // 禁用插件方法
    public static function deactivate()
    {
        return _t('插件已禁用。');
    }

    // 插件配置方法
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $notice = new Typecho_Widget_Helper_Form_Element_Textarea(
            'notice', 
            NULL, 
            '', 
            _t('公告内容'), 
            _t('请输入公告内容，插入&lt;br&gt;换行。')
        );
        $form->addInput($notice);
    }

    // 插件个人用户配置方法（可选）
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    // 插件实现方法
    public static function header()
    {
        $notice = Typecho_Widget::widget('Widget_Options')->plugin('PopupNotice')->notice;
        if ($notice) {
            echo '<style>
                .popup-notice-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.6);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                }
                .popup-notice-box {
                    background: linear-gradient(145deg, #ffffff, #f0f0f0);
                    padding: 18px;
                    max-width: 500px;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
                    position: relative;
                    text-align: left;
                    font-family: Arial, sans-serif;
                    color: #333;
                }
                .popup-notice-box p {
                    margin: 0 0 20px;
                    font-size: 18px;
                    line-height: 1.6;
                }
                .popup-notice-button {
                    position: absolute;
                    bottom: 15px;
                    right: 15px;
                    padding: 7px 12px;
                    background: #007bff;
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: background 0.3s ease;
                }
                .popup-notice-button:hover {
                    background: #0056b3;
                }
            </style>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    if (!localStorage.getItem("popup_notice_shown")) {
                        var noticeContent = ' . json_encode($notice) . ';
                        var noticeBox = document.createElement("div");
                        noticeBox.classList.add("popup-notice-overlay");
                        noticeBox.innerHTML = `<div class="popup-notice-box">
                            <p>${noticeContent}</p>
                            <button id="closeNotice" class="popup-notice-button">确认</button>
                        </div>`;
                        document.body.appendChild(noticeBox);
                        document.getElementById("closeNotice").onclick = function() {
                            noticeBox.style.display = "none";
                            localStorage.setItem("popup_notice_shown", "true");
                        };
                    }
                });
            </script>';
        }
    }
}
?>
