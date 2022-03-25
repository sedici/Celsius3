FROM httpd:2

RUN apt update && apt upgrade --yes;
COPY httpd.conf /usr/local/apache2/conf/local.conf
RUN echo "Include /usr/local/apache2/conf/local.conf" >> /usr/local/apache2/conf/httpd.conf