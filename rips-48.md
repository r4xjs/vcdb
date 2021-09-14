---

title: rips-48
author: raxjs
tags: [php]

---

JWT token verification.

<!--more-->
{{< reference src="https://twitter.com/ripstech/status/1124325237967994880" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
class JWT {
    public function verifyToken($data, $signature) {
        $pub = openssl_pkey_get_public("file://pub_key.pem");
        $signature = base64_decode($signature);
        if(openssl_verify($data, $signature, $pub)) {
            $object = json_decode(base64_decode($data));
            $this->loginAsUser($object);
        }
    }
}
(new JWT())->verifyToken($_GET['d'], $_GET['s']);

{{< /code >}}

# Solution
{{< code language="php" highlight="6-9,16" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
class JWT {
    public function verifyToken($data, $signature) {
        $pub = openssl_pkey_get_public("file://pub_key.pem");
        $signature = base64_decode($signature);
        // 2) in case of an openssl_verify error path, -1 is returned
        //    var_dump(-1 == true);
        //    bool(true)
        //    --> login bypass and $data can include arbitrary json
        if(openssl_verify($data, $signature, $pub)) {
            $object = json_decode(base64_decode($data));
            $this->loginAsUser($object);
        }
    }
}
// 1) verifyToken arguemtns are bot user input
(new JWT())->verifyToken($_GET['d'], $_GET['s']);


// https://www.openssl.org/docs/man1.1.0/man3/EVP_VerifyInit.html
// /* {{{ proto int openssl_verify(string data, string signature, mixed key[, mixed method])
//    Verifys data */
// PHP_FUNCTION(openssl_verify)
// {
// 	zval *key;
// 	EVP_PKEY *pkey;
// 	int err = 0;
// 	EVP_MD_CTX *md_ctx;
// 	const EVP_MD *mdtype;
// 	zend_resource *keyresource = NULL;
// 	char * data;
// 	size_t data_len;
// 	char * signature;
// 	size_t signature_len;
// 	zval *method = NULL;
// 	zend_long signature_algo = OPENSSL_ALGO_SHA1;
// 
// 	if (zend_parse_parameters(ZEND_NUM_ARGS(), "ssz|z", &data, &data_len, &signature, &signature_len, &key, &method) == FAILURE) {
// 		RETURN_THROWS();
// 	}
// 
// 	PHP_OPENSSL_CHECK_SIZE_T_TO_UINT(signature_len, signature);
// 
// 	if (method == NULL || Z_TYPE_P(method) == IS_LONG) {
// 		if (method != NULL) {
// 			signature_algo = Z_LVAL_P(method);
// 		}
// 		mdtype = php_openssl_get_evp_md_from_algo(signature_algo);
// 	} else if (Z_TYPE_P(method) == IS_STRING) {
// 		mdtype = EVP_get_digestbyname(Z_STRVAL_P(method));
// 	} else {
// 		php_error_docref(NULL, E_WARNING, "Unknown signature algorithm.");
// 		RETURN_FALSE;
// 	}
// 	if (!mdtype) {
// 		php_error_docref(NULL, E_WARNING, "Unknown signature algorithm.");
// 		RETURN_FALSE;
// 	}
// 
// 	pkey = php_openssl_evp_from_zval(key, 1, NULL, 0, 0, &keyresource);
// 	if (pkey == NULL) {
// 		if (!EG(exception)) {
// 			php_error_docref(NULL, E_WARNING, "supplied key param cannot be coerced into a public key");
// 		}
// 		RETURN_FALSE;
// 	}
// 
// 	md_ctx = EVP_MD_CTX_create();
// 	if (md_ctx == NULL ||
// 			!EVP_VerifyInit (md_ctx, mdtype) ||
// 			!EVP_VerifyUpdate (md_ctx, data, data_len) ||
//          ////////////// check is done here /////////////////////////
// 			(err = EVP_VerifyFinal(md_ctx, (unsigned char *)signature, (unsigned int)signature_len, pkey)) < 0) { 
// 		php_openssl_store_errors();
// 	}
// 	EVP_MD_CTX_destroy(md_ctx);
// 
// 	if (keyresource == NULL) {
// 		EVP_PKEY_free(pkey);
// 	}
// 	RETURN_LONG(err);
// }
// /* }}} */


// int EVP_VerifyFinal_ex(EVP_MD_CTX *ctx, const unsigned char *sigbuf,
//                        unsigned int siglen, EVP_PKEY *pkey, OSSL_LIB_CTX *libctx,
//                        const char *propq)
// {
//     unsigned char m[EVP_MAX_MD_SIZE];
//     unsigned int m_len = 0;
//     int i = 0;
//     EVP_PKEY_CTX *pkctx = NULL;
// 
//     if (EVP_MD_CTX_test_flags(ctx, EVP_MD_CTX_FLAG_FINALISE)) {
//         if (!EVP_DigestFinal_ex(ctx, m, &m_len))
//             goto err;
//     } else {
//         int rv = 0;
//         EVP_MD_CTX *tmp_ctx = EVP_MD_CTX_new();
// 
//         if (tmp_ctx == NULL) {
//             ERR_raise(ERR_LIB_EVP, ERR_R_MALLOC_FAILURE);
//             return 0;
//         }
//         rv = EVP_MD_CTX_copy_ex(tmp_ctx, ctx);
//         if (rv)
//             rv = EVP_DigestFinal_ex(tmp_ctx, m, &m_len);
//         EVP_MD_CTX_free(tmp_ctx);
//         if (!rv)
//             return 0;
//     }
// 
//     i = -1;
//     pkctx = EVP_PKEY_CTX_new_from_pkey(libctx, pkey, propq);
//     if (pkctx == NULL)
//         goto err;
//     if (EVP_PKEY_verify_init(pkctx) <= 0)
//         goto err;
//     if (EVP_PKEY_CTX_set_signature_md(pkctx, EVP_MD_CTX_get0_md(ctx)) <= 0)
//         goto err;
//     i = EVP_PKEY_verify(pkctx, sigbuf, siglen, m, m_len);
//  err:
//     EVP_PKEY_CTX_free(pkctx);
//     return i;
// }
// 
// int EVP_VerifyFinal(EVP_MD_CTX *ctx, const unsigned char *sigbuf,
//                     unsigned int siglen, EVP_PKEY *pkey)
// {
//     return EVP_VerifyFinal_ex(ctx, sigbuf, siglen, pkey, NULL, NULL);
// }




{{< /code >}}
