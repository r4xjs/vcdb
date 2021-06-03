using System;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Cryptography;
using Microsoft.AspNetCore.Mvc;
using Microsoft.IdentityModel.Tokens;
using Org.BouncyCastle.Crypto;
using Org.BouncyCastle.Crypto.Parameters;
using Org.BouncyCastle.Security;
namespace core_api.Controllers
{
    public class ApiController : Controller
    {
        private readonly string publicKey;        
	private readonly string authTokenIssuer;
        
        public ApiController(string publicKey, string authTokenIssuer)     
        {
            this.publicKey = publicKey;
            this.authTokenIssuer = authTokenIssuer;
        }
        
        private String ExportPublicKey(RSACryptoServiceProvider rsa)
        {
            throw new NotImplementedException();
        }
        private RSACryptoServiceProvider ImportKeyParameters(string publicKey)
        {
            throw new NotImplementedException();
        }
	    
        public JwtSecurityToken ValidateToken(string token) {           
            byte[] keyBytes = Convert.FromBase64String(publicKey);
            var keyParams = (RsaKeyParameters)PublicKeyFactory.CreateKey(keyBytes);
            var rsaParams = new RSAParameters {
                Modulus = keyParams.Modulus.ToByteArrayUnsigned(),
                Exponent = keyParams.Exponent.ToByteArrayUnsigned() 
            };
            var rsa = new RSACryptoServiceProvider();
            rsa.ImportParameters(rsaParams);
            rsa.KeySize = 4096;
            var validationParameters = new TokenValidationParameters {
                RequireExpirationTime = true,
                RequireSignedTokens = true,
                ValidIssuer = authTokenIssuer,
                ValidateIssuer = true,
                ValidateLifetime = true,
                IssuerSigningKey = new RsaSecurityKey(rsa)
            };
            var handler = new JwtSecurityTokenHandler();
            handler.ValidateToken(token, validationParameters, out SecurityToken validatedSecurityToken);
            var validatedJwt = validatedSecurityToken as JwtSecurityToken;
            return validatedJwt;
        }       
    }
}