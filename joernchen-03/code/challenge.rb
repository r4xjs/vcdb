# coding: utf-8
require 'sinatra'

post '/ping' do
    if params['host'] =~ /^(\d{1,3}\.){3}(\d){1,3}$/
        return `ping -c 4 #{params['host']}`
    end
    return "Invalid IP address"
end
