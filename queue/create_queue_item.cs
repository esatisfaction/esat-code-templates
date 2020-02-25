using Newtonsoft.Json;
using System;
using System.IO;
using System.Net;

namespace YOUR_NAMESPACE
{
    public class Queue
    {
        public object Insert()
        {
            try
            {
                // Create API Payload
                var payloadObject = new
                {
                    responder_channel_identifier = "test@esat.com",
                    send_time = "2018-01-01T00:00:00+03:00",
                    metadata = new
                    {
                        questionnaire = new
                        {
                            transaction_id = "12345",
                            transaction_date = "14/08/2019",
                            store_id = "STR123"
                        },
                        responder = new
                        {
                            name = "John Doe",
                            email = "john@doe.com",
                            phone_number = "6912345678"
                        }
                    }
                };
                // Get payload as JSON string
                string payload = JsonConvert.SerializeObject(payloadObject);

                // Prepare HTTP Request
                var httpWebRequest = (HttpWebRequest)WebRequest.Create("https://api.e-satisfaction.com/v3.2/q/questionnaire/QUESTIONNAIRE_ID/pipeline/PIPELINE_ID/queue/item");
                httpWebRequest.ContentType = "application/json";
                httpWebRequest.Method = "POST";

                // Set authentication headers
                httpWebRequest.Headers.Set("esat-auth", "YOUR_TOKEN");
                httpWebRequest.Headers.Set("esat-domain", "YOUR_WORKING_DOMAIN");

                // Set payload on Request
                using (var streamWriter = new StreamWriter(httpWebRequest.GetRequestStream()))
                {
                    streamWriter.Write(payload);
                }

                // Make the HTTP Request and get the result
                var result = "";
                var httpResponse = (HttpWebResponse)httpWebRequest.GetResponse();
                using (var streamReader = new StreamReader(httpResponse.GetResponseStream()))
                {
                    result = streamReader.ReadToEnd();
                }

                // Read result back to object
                return JsonConvert.DeserializeObject(result);
            }
            catch (WebException ex)
            {
                Console.Write(ex);
            }

            return null;
        }
    }
}
