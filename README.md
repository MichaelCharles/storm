# Storm for Cloud9

This is a command line utility written in PHP for conjuring up muliple instances of Amazon's Cloud9 browser-based IDE inside Docker containers for educational purposes.

## Usage

To create Cloud9 instances, use the command `storm conjure` or `storm make` followed by the number of instances you want. This will create a `workspaces` folder in your home directory and generate a `docker-compose.yml` file there.

From there you can either use the generated files on your own, or use the Storm CLI to manage them. You can use `storm up` to bring up the containers, `storm down` to bring them down, or `storm clear` to bring down the containers and then delete the `docker-compose.yml` file along with the user workpaces.

As of this current version the generated instances are branded with the logo for S2 Co, Ltd. as it was written for use within that company. Future releases may (or may not) include a non-branded option.