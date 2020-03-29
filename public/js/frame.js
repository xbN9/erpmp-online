/**
 * Desc
 *
 * @author Guan Yongze <GuanYongze@163.com>
 * @link http://www.GuanYongze.com/
 * @copyright Copyright &copy; 2009 Guan Yongze 
 * @license http://www.GuanYongze.com/license
 * @version 1.0.0
 * @package xoyo
 * @since 1.0.0
 */

function Frame() {

	/**
	 * 框架顶部的消息提示
	 *
	 * @param string msg 信息文字
	 * @param string className 附加样式类名
	 * @param integer time 消息自动消失时间
	 */
	this.messageTip = function (msg, className, time){
		var tipDiv = $('#messageTip');
		
		if(!msg){
			$('#messageTip').fadeOut('fast');
			return;
		}
		
		time = time || 5000;
		className = className || '';
		
		tipDiv.hide();
		tipDiv.find('span:first-child').removeClass().addClass(className).html(msg);
		tipDiv.fadeIn('normal');
		if (tipDiv.queue().length <= 1){
			setTimeout(function(tipDiv) {
					$('#messageTip').fadeOut('slow');
				}, 
				time
			);
		}
	}

	/**
	 *	弹窗
	 *
	 * @param url 弹窗内iframe要加载的url
	 * @param width 宽，如500 不用写单位，默认500px
	 * @param height 高，如300 不用写单位，默认300px
	 * @param title 标题 默认“对话框”
	 * @param icon 图标 默认sytle/icon/kingsoftS.png
	 * @param top 对上边的距离 要加上单位 如100px、18%
	 * @param masker 是否做用蒙层 true|false
	 * @param maskerAsCloser 点击蒙层时是否关闭弹窗 true|false
	 */
	this.popupWindow = function (url, width, height, title, icon, top, masker, maskerAsCloser){

		var width = width || '500';
		var height = height || '300';
		var top = top || '18%';
		
		var marginLeft = - (width / 2);

		var icon = icon || './style/icon/kingsoftS.png';

		var title = title || '对话框';
		width = width + 'px';
		height = height + 'px';
		
		$('#framePopupLayerIframe').attr('src', url);
		
		$('#framePopupLayer').css({'width':width, 'margin-left': marginLeft, 'top':top});
		$('#framePopupLayerHeader').css({'width':width});
		$('#framePopupLayerInner').css({'width':width, 'height':height});

		$('#framePopupLayerIcon').css({'background-image':'url('+icon+')'});
		$('#framePopupLayerTitle').html(title);

		masker = masker || true;
		maskerAsCloser = maskerAsCloser || false;
	
		$('#framePopupLayer').popupLayer({
			opener : '.framePopupLayerOpener',
			closer : '.framePopupLayerCloser',
			modal : false,
			openStyle : 'fadeIn',
			openSpeed : '1000',
			masker: masker,
			toTop: true,
			maskerAsCloser: maskerAsCloser,
			maskerCss : { opacity: 0.5, backgroundColor : 'black' }
		})
		.popupLayerShow();
	}
	
	/**
	 * 关闭弹出层，如果url参数不为空，则主窗口会转到url指定的页面
	 *
	 * @param string url 关闭弹出窗口后主窗口转到的页面
	 */
	this.closePopupWindow = function(url){
		$('#framePopupLayer').popupLayer().popupLayerHide();

		if(url){
			$('#mainFrame').attr('src', url);
		}
	}

	/**
	 * 模拟confirm
	 *
	 */
	 this.confirm = function (message, onSuccessCode, width, height, title, icon, top, masker, maskerAsCloser){
		
		var width = width || '200';
		var height = height || '100';
		var top = top || '18%';
		
		var marginLeft = - (width / 2);

		var icon = icon || './style/icon/moderatorS.png';

		var title = title || '确认框';

		width = width + 'px';
		height = height + 'px';
		
		$('#confirmPopupLayer').css({'width':width, 'margin-left': marginLeft, 'top':top});
		$('#confirmPopupLayerHeader').css({'width':width});
		$('#confirmPopupLayerInner').css({'width':width, 'height':height});
		$('#confirmPopupLayerIcon').css({'background-image':'url('+icon+')'});
		$('#confirmPopupLayerTitle').html(title);
		$('#confirmPopupLayerMessage').html(message);
		$('#confirmPopupLayerYes').click(onSuccessCode);

		masker = masker || true;
		maskerAsCloser = maskerAsCloser || false;
	
		$('#confirmPopupLayer').popupLayer({
			opener : '.confirmPopupLayerOpener',
			closer : '.confirmPopupLayerCloser, #confirmPopupLayerNo, #confirmPopupLayerYes',
			modal : false,
			openStyle : 'fadeIn',
			openSpeed : '1000',
			masker: masker,
			toTop: true,
			maskerAsCloser: maskerAsCloser,
			maskerCss : { opacity: 0.5, backgroundColor : 'black' }
		})
		.popupLayerShow();
	 }

	/**
	 * 关闭弹出层，如果url参数不为空，则主窗口会转到url指定的页面
	 *
	 * @param string url 关闭弹出窗口后主窗口转到的页面
	 */
	this.closeConfirm = function(url){
		$('#confirmPopupLayer').popupLayer().popupLayerHide();

		if(url){
			$('#mainFrame').attr('src', url);
		}
	}

};
var frame = new Frame;
