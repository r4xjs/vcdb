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
            var xmlDoc = new XmlDocument { XmlResolver = null };
            xmlDoc.LoadXml(content); 
            var rootItem = (XmlElement)xmlDoc.SelectSingleNode("root");
            var dataType = Type.GetType(rootItem.GetAttribute("data"));
            var reader = new StringReader(rootItem.InnerXml);
            ExchangeData exchange = Import(dataType, reader);
            return exchange.ToString();
        }
        private static ExchangeData Import(Type t, StringReader r) {
            XmlSerializer serializer = new XmlSerializer(t);
            XmlTextReader textReader = new XmlTextReader(r);
            ExchangeData data = (ExchangeData)serializer.Deserialize(textReader);
            return data;
        }
    }
}