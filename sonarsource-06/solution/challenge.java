class F00 {
    ...
    @RequestMapping(value = {"/api/adapter/{adapter}/activate/{b}"},
		    method = RequestMethod.POST, produces = "application/json")
    public String activateAdapter(@PathVaraible("adapter") String connName,
				  @PathVaraible("b") Integer b) throws IOException {
                  // 1) connName and b are user input
	logger.info("activating adapter.");
    // 2) user can controll where the post request is going
    //    and there by also controll what is returned
	HttpPost post = new HttpPost("https://" + connName + "/v1/boot");
	String requestBody = "{\"activate\":" + Integer.toString(b) + "}";
	StringEntity requestEntity = new StringEntity(
						      requestBody,
						      ContentType.APPLICATION_JSON
						      );
	post.setEntity(requestEntity);
	try(CloseableHttpClient httpClient = HttpClients.createDefault();
        // 3) response can be under controll of the user
	    CloseableHttpResponse response = httpClient.execute(post)) {
	    logger.info("response:" + response.getEntity());
	    return EntityUtils.toString(response.getEntity());
	}
    }
    ...
}

// First: by sending the post request to an user controlled host, he can
//        read all http header which may leak some auth keys or similar.
// Second: the result can't be trusted. It may be intresting to see where
//         the result is used after returned by the user.
