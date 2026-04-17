# Diagram Arsitektur Cloud

```mermaid
flowchart LR
    user[User Browser]
    igw[Internet Gateway]

    subgraph vpc[VPC]
        subgraph public[Public Subnet]
            ec2[EC2 Instance\nDocker Container\nLaravel App]
        end

        subgraph private[Private Subnet]
            rds[(Amazon RDS MySQL)]
        end

        nat[NAT Gateway (Opsional)]
    end

    gha[GitHub Actions CI/CD]
    gh[GitHub Repository]
    s3[(Amazon S3 Bucket)]

    user --> igw --> ec2
    ec2 --> rds
    ec2 --> s3

    gh --> gha --> ec2
    nat -. outbound private traffic .-> private
```

## Komponen

- VPC dengan public subnet untuk EC2 dan private subnet untuk RDS.
- EC2 menjalankan aplikasi Laravel dalam container Docker.
- RDS menyimpan semua data CRUD aplikasi.
- S3 menyimpan file upload foto laporan sampah.
- GitHub Actions menjalankan test dan deploy otomatis ke EC2.
- NAT Gateway opsional untuk akses internet dari private subnet.
