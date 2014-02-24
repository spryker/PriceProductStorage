network:
  loadbalancers_interface: lo
  project_interface: lo

newrelic:
  license_key:
  api_key:

environments:
  development:
    database:
      zed:
        hostname: localhost
        username: development
        password: mate20mg
    tomcat:
      port_suffix: '0007'
      min_heap_size: 128m
      max_heap_size: 512m
      max_perm_size: 256m
    solr:
      hostname: localhost
    queue:
      stomp_port: 40006
    files:
      provider: rackspace
      api_username: xxx
      api_key: xxx
    stores:
      DE:
        yves:
          port: 10000
          hostnames:
            - www-development.project-boss.net
        zed:
          port: 10001
          hostname: zed-development.project-boss.net
          htpasswd_file:
        dwh:
          hostname: dwh.project-boss.net

  testing:
    database:
      zed:
        hostname: localhost
        username: development
        password: mate20mg
    tomcat:
      port_suffix: '1007'
      min_heap_size: 128m
      max_heap_size: 512m
      max_perm_size: 256m
    solr:
      hostname: localhost
    queue:
      stomp_port: 41006
    files:
      provider: rackspace
      api_username: xxx
      api_key: xxx
    stores:
      DE:
        yves:
          port: 11000
          hostnames:
            - www-development.project-boss.net
        zed:
          port: 11001
          hostname: zed-development.project-boss.net
          htpasswd_file:
        dwh:
          hostname: dwh.project-boss.net

# The key below is used for deployment - from deployment server user root must be able to log in to all other
# servers as user root.
# If we're using salt-cloud to create cloud VM's - it will automatically generate /root/.ssh/id_rsa
# on salt master and copy appropiate id_rsa.pub to minions to /root/.ssh/authorized_keys.
#
# Paste the content of /root/.ssh/id_rsa from salt-master here:
server_env:
  ssh:
    id_rsa: |
      -----BEGIN RSA PRIVATE KEY-----
      MIIEowIBAAKCAQEAxY2OQI67aFsKdSN9k0eeW9rv25zLsXkYQ8opOJmEs//b39Sh
      gaPXFjXB2LheCtpeQYJuhr0liaR8z900LTnkd5Ck8irYgRdI4wwDYrJStqGmi/W9
      0JBOSaSZKB5Bq4ZtmRzp2G2UxGG2Cp8Z4pvMzKi1AeyWCCh1y/dk+XCa2VUrffw3
      LNODRxGm5T3ks99ClOq/gczXPOJesHavYpguYJZ4xAyyCD3sgwVtDO6mz+/D7fMM
      p7qlM6+RyGUkoVTHep8GXxBLWpNL0m0rxIC8O8cWUdzRxXKuhTp3HJVcSBzluPZM
      LF8HgSYtrtuV/g49ogpDFpyRpP+o2kU+Z9ZMowIDAQABAoIBADhunGEipLRFtXIK
      xldpEPqKSxQ/8QvsXJsYx7esWvUUNJn24n/m7o0gpBQlbm/JOz2ZZbtFktCD1UeH
      l90HeZUSE5w90wTlQuPgzaLG78vN14aJl0RZxJNS2pFUO0wlJW+ki8RQYTvL8bWN
      B1btTI517ubyz1TbQfMW45hBq2pDYl3AMITFw9RPFhxWqX0LCbd2+E4Kb24Gucw8
      zGedD3jiHQQEDoWa8MzA3R27cu8h38/y3VuZwlqAH8LYZz3Pa3hzsPijR9Hu01Ti
      PRRU/gl8/6867PNcGNrPp+3KYzWiRnMkHKM0NUkLrjrmu282/+9RlcoIvE7HSQNa
      78WXi1ECgYEA7/HM6XQEri37InQgBMXCseHj0hdg3vYjwLyc49GScSEk4gE7osFt
      v3gnveQumPOc097oImm/YjMABTI0Adea0ZDQrzqoRcrl8Y/HZhQsHC3FzWPgjp4M
      NSGDxdsqkvvqxbucaTZKNjEvt+Foj9HwN1PgfRg8oTKRbn15k1iF78sCgYEA0sWY
      qWxrHB9MHZcbhmvF285vXNJS8+hqd3WZiy5vzCBeBajHiO3vt6o0pFyjHB18SkWR
      NToDqJK8gk3MBowZoxo2Izq4OuAIhEk3mPAl/XxijJL8rwqrZ4EpV8N8B6OqMw9T
      TGogBiWng3uUqKO8WIMQCVv/LIaQosWm9tKpy4kCgYAB3vHJFDWBIiymHR+uydf1
      yRwcpEAGHQc0VoQmppistyRxeq77WuS/LHGq4l+Wo7eiU0eeFRL/8gPbBoQvS34S
      ij2GUD/Fo4pdctCMNDYP1i+HpXK3yfWwaF19qnLPiJVRC2Bx1ZGIkKOdnG1TScDE
      vauvbgPzAdDuep37DyKkNwKBgGsfzMiHdyTy7z+21mGKkyR1lnop18lp3frbRW/M
      6TtpVAAyWT/KFuVVV64V+zzF667gDr5rvwJFmhPsMH8/Y6RXJR7gsFQUG6AIin5r
      yBM+zeO+rTjWBmnz5qxZicdkMpQC/QZAhgg5yRr1i4fuuEKQUm0/WcEqn4ZrSMH4
      Lh5pAoGBALcsa0Ih3XfeG/KhvkdrDiwreCn4h/78iMBRtkDE8X0/e/+OB+GNkR9b
      nK6pZcl5Cm0Exn6PRsiuhuqgZ7w2OcV4L3wld2Ro8aNsnTthqwe2TUsW0Bjzy+tx
      D2U7XJ1kDaA8iCexqsJmUGjSJ8p2wERuolPo0cGEEZq9c3g84FC6
      -----END RSA PRIVATE KEY-----

