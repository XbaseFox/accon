##
#  TAIGA
#
#  用途: プロジェクト管理プラットフォーム
#  構成: image[ubuntu-nginx-python3-ruby-nodejs-redis-postgresql] + TAIGA
#
# Part of the ACCON.
#
# Copyright (c) 2017 Maemori Fumihiro
# This software is released under the MIT License.
# http://opensource.org/licenses/mit-license.php
#
# @version    1.05
# @author     Maemori Fumihiro
# @link       https://kurobuta.jp
FROM accon/ubuntu-nginx-circus-postgresql:1.01
MAINTAINER Maemori Fumihiro

# Add user
RUN \
  groupadd -g 1100 taiga && \
  useradd -u 1100 -g 1100 -m -d /home/taiga -s /bin/bash -c 'taiga' taiga && \
  echo 'taiga:develop' | chpasswd && \
  gpasswd -a taiga develop && \
  gpasswd -a taiga sudo && \
  gpasswd -a taiga postgres && \
  gpasswd -a taiga rabbitmq && \
  gpasswd -a taiga www-data && \
  echo "taiga ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers && \
  echo "### Virtualenvwrapper\nif [ -f /usr/local/bin/virtualenvwrapper.sh ]; then\n  export VIRTUALENVWRAPPER_PYTHON=/usr/bin/python3\n  export WORKON_HOME=/home/taiga/.virtualenvs\n  source /usr/local/bin/virtualenvwrapper.sh\nfi" >> /home/taiga/.bashrc

# Create database
RUN \
  service postgresql start && \
  sudo -u postgres createuser root && \
  sudo -u postgres createuser taiga && \
  sudo -u postgres createdb taiga -O taiga && \
  sudo -u postgres psql -c "alter role taiga with password 'taiga';"

# Install taiga-back
RUN \
  git clone https://github.com/taigaio/taiga-back.git /develop/workspace/taiga-back && \
  cd /develop/workspace/taiga-back && \
  git checkout stable

# Taiga Configuration
ADD ./conf/local.py /develop/workspace/taiga-back/settings/local.py

# Add Taiga-back Configuration
ADD ./conf/taiga.ini /etc/circus/conf.d/

# Add Taiga-celery Configuration
ADD ./conf/taiga-celery.ini /etc/circus/conf.d/

# Install taiga-front
RUN \
  git clone https://github.com/taigaio/taiga-front-dist.git /develop/workspace/taiga-front-dist && \
  cd /develop/workspace/taiga-front-dist && \
  git checkout stable

# Add Taiga-front Configuration
ADD ./conf/conf.json /develop/workspace/taiga-front-dist/dist/

# Install taiga-events
RUN \
  git clone https://github.com/taigaio/taiga-events.git /develop/workspace/taiga-events

# Add Taiga-events Configuration
ADD ./conf/events-config.json /develop/workspace/taiga-events/config.json
ADD ./conf/taiga-events.ini /etc/circus/conf.d/

# Save the TAIGA
RUN \
  mkdir /develop/archive && \
  mv /develop/workspace/taiga-back /develop/archive/ && \
  mv /develop/workspace/taiga-front-dist /develop/archive/ && \
  mv /develop/workspace/taiga-events /develop/archive/

# Nginx conf
ADD ./conf/default /etc/nginx/sites-available/

# TAIGA setting file
ADD ./module/taiga_setting.sh /etc/service/taiga_setting
RUN chmod +x /etc/service/taiga_setting
ADD ./module/progress.sh /etc/service/progress
RUN chmod +x /etc/service/progress

# Startup file
ADD ./module/run.sh /etc/service/run
RUN chmod +x /etc/service/run

# Public port
EXPOSE 80 443 5432 15672

# Startup
CMD ["/etc/service/run"]
