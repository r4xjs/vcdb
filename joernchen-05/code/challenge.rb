def check
  fullpath = request.path_info
  hmaced = "#{params[:email]}/#{params[:expire]}/"
  email = Base64.urlsafe_decode64 params[:email]
  expire = params[:expire].to_i
  digest = OpenSSL::Digest.new('sha512')
  hmac = OpenSSL::HMAC.hexdigest(digest, File.read("secret"), hmaced)
  t = Time.now()
  time_left = Time.at(expire) > t
  if not hmac == params[:hmac] and not time_left
   return false
  end
  return [email,expire]
end
