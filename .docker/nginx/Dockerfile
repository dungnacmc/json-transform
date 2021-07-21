FROM nginx:latest

COPY ./.docker/nginx/server.conf /etc/nginx/conf.d/default.conf
COPY ./.docker/nginx/ssl/app.crt /etc/nginx/ssl/app.crt
COPY ./.docker/nginx/ssl/app.key /etc/nginx/ssl/app.key
COPY ./.docker/nginx/includes /etc/nginx/includes

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]
