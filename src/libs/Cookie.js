/**
 * 封装cookie 基础操作, 代码取自axios
 */

import {getType} from '@/libs/util';

class Cookie{

	static write(name, value, expires, path, domain, secure) {
		var cookie = [];
		cookie.push(name + '=' + encodeURIComponent(value));

		if (getType(expires) == 'number') {
			expires = (+new Date()) + expires * 1000;
			cookie.push('expires=' + new Date(expires).toGMTString());
		}

		if (getType(path) == 'string') {
			cookie.push('path=' + path);
		}

		if (getType(domain) == 'string') {
			cookie.push('domain=' + domain);
		}

		if (secure === true) {
			cookie.push('secure');
		}

		document.cookie = cookie.join('; ');
	}

	static read(name) {
		var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
		return (match ? decodeURIComponent(match[3]) : null);
	}

	static remove(name) {
		this.write(name, '', Date.now() - 86400000);
	}
}

export default Cookie;