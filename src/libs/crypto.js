/**
 * 系统数据加密模块
 * 
 * 参考: http://www.sosout.com/2018/09/05/cryptojs-tutorial.html
 * 参考: https://blog.zhengxianjun.com/2015/05/javascript-crypto-js/
 * 
 */
import CryptoJS from "crypto-js";

const key = CryptoJS.enc.Utf8.parse("6u0zozYEKaocxdhd");
const iv = CryptoJS.enc.Utf8.parse("k7urtA5znSOA8Cqr");

/**
 * md5加密数据
 * 
 * @param {object/string} data 需要进行加密的数据
 */
export function md5(data=''){
	if (typeof data == 'object') {
		data = JSON.stringify(data);
	}

	let result = CryptoJS.MD5(data).toString();
	return result;
}

/**
 * AES加密
 * 
 * @param {object/string} data 需要进行加密的数据
 */
export function aes_encrypt(data){
	let result = '';

	if (typeof data == 'object') {
		data = JSON.stringify(data);
	}

	if (typeof data == 'string') {
		const parse = CryptoJS.enc.Utf8.parse(data);
		let encrypted = CryptoJS.AES.encrypt(parse, key, {
			iv: iv,
			mode: CryptoJS.mode.CBC,
			padding: CryptoJS.pad.Pkcs7
		});

		result = encrypted.ciphertext.toString();
	}

    return result;
}

/**
 * AES解密
 * 
 * @param {string} data 需要解密的字符串
 * 
 */
export function aes_decrypt(data){
	const parse_str = CryptoJS.enc.Hex.parse(data);
    const srcs = CryptoJS.enc.Base64.stringify(parse_str);
    const decrypt = CryptoJS.AES.decrypt(srcs, key, {
		iv: iv,
		mode: CryptoJS.mode.CBC,
		padding: CryptoJS.pad.Pkcs7
    });
    const decrypted_str = decrypt.toString(CryptoJS.enc.Utf8);
    return decrypted_str.toString();
}