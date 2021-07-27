using System;
using System.IO;
using System.Xml;
using System.Xml.Serialization;
using Microsoft.AspNetCore.Mvc;
[Serializable]
public class ExchangeData {
}
namespace core_api.Controllers {
    public class ShareDataController : Controller {
        [Route("import/exchange")]
        [HttpPost]
        public string ImportExchangeData(string content) {
	    // 1) $content is user controlled
	    // https://docs.microsoft.com/en-us/dotnet/api/system.xml.xmldocument.xmlresolver?view=net-5.0
	    // External Entity Injection will not work
            var xmlDoc = new XmlDocument { XmlResolver = null };
            xmlDoc.LoadXml(content);
            var rootItem = (XmlElement)xmlDoc.SelectSingleNode("root");
            var dataType = Type.GetType(rootItem.GetAttribute("data"));
            var reader = new StringReader(rootItem.InnerXml);
	    // 2) $rootItem, $dataType and $reader is user controlled
            ExchangeData exchange = Import(dataType, reader);
            return exchange.ToString();
        }
        private static ExchangeData Import(Type t, StringReader r) {
	    // 3) therefore $t and $r is user controlled and is passed to
	    //    XmlSerializer. An attacker can determine the type and the
	    //    content of the deserialization --> insecure deserialization
            XmlSerializer serializer = new XmlSerializer(t);
            XmlTextReader textReader = new XmlTextReader(r);
            ExchangeData data = (ExchangeData)serializer.Deserialize(textReader);
            return data;
        }
    }
}



