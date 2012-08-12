class SVGConvertInkscape < SVGConvert
include OSFunctions

	def generateCommand()
		if OSFunctions::is_windows?
			cmd = "inkscapec.exe ";
		else
			cmd = "inkscape "
		end
		
		case @format
			when "ps":
				cmd = cmd + "--export-ps=\"" + @outfile + "\" ";
			when "eps":
				cmd = cmd + "--export-eps=\"" + @outfile + "\" ";
			when "pdf":
				cmd = cmd + "--export-pdf=\"" + @outfile + "\" ";
			else
				cmd = cmd + "--export-png=\"" + @outfile + "\" ";
		end
		
		unless @width == nil		
			cmd = cmd + "--export-width=" + @width.to_s() + " "
		end
		
		unless @dpi == nil
			cmd = cmd + "--export-dpi=" + @dpi.to_s() + " "
		end

		cmd = cmd + "\"" + @infile + "\"";		
	
		return cmd;
	end
end
