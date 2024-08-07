FROM bitnami/codeigniter:3.1.11-debian-10-r446

#install all php lib
RUN apt update
RUN apt upgrade -y
RUN apt install -y apt-utils
# Install Git
RUN apt-get install -y git
#install nano
RUN apt-get install -y nano

#COPY . /app

RUN echo "session.save_path=\"/tmp\"" >> /opt/bitnami/php/lib/php.ini
