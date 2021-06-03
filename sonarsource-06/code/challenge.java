class F00 {
    ...
    @RequestMapping(value = {"/api/adapter/{adapter}/activate/{b}"},
		    method = RequestMethod.POST, produces = "application/json")
    public String activateAdapter(@PathVaraible("adapter") String connName,
				  @PathVaraible("b") Integer b) throws IOException {
	logger.info("activating adapter.");
	HttpPost post = new HttpPost("https://" + connName + "/v1/boot");
	String requestBody = "{\"activate\":" + Integer.toString(b) + "}";
	StringEntity requestEntity = new StringEntity(
						      requestBody,
						      ContentType.APPLICATION_JSON
						      );
	post.setEntity(requestEntity);
	try(CloseableHttpClient httpClient = HttpClients.createDefault();
	    CloseableHttpResponse response = httpClient.execute(post)) {
	    logger.info("response:" + response.getEntity());
	    return EntityUtils.toString(response.getEntity());
	}
    }
    ...
}
