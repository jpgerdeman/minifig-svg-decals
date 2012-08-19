module OSFunctions

  def self.is_windows?
    require 'rbconfig'
    RbConfig::CONFIG['host_os'] =~ /mingw|mswin/
  end

end
